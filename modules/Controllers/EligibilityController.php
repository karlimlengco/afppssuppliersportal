<?php

namespace Revlv\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use Excel;
use Sentinel;

use \Revlv\Settings\Units\UnitRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\ProcurementCenters\ProcurementCenterRepository;
use Revlv\Settings\Suppliers\SupplierEloquent;
use \Revlv\Settings\Suppliers\Attachments\AttachmentRepository;

class EligibilityController extends Controller
{

    /**
     * [$blankRfq description]
     *
     * @var [type]
     */
    protected $model;


    /**
     * @param center $center
     */
    public function __construct(UnitPurchaseRequestRepository $model)
    {
        $this->model  =   $model;
        parent::__construct();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UnitPurchaseRequestRepository $model)
    {

        $user       =   \Sentinel::getUser();
        $suppliers  =   json_decode($user->suppliers);

        $resource   =   SupplierEloquent::select([
            'supplier_attachments.name',
            'supplier_attachments.type',
            'supplier_attachments.issued_date',
            'supplier_attachments.validity_date',
            'supplier_attachments.ref_number',
            'supplier_attachments.place',
        ]);
        $resource   =   $resource->leftJoin('supplier_attachments', 'supplier_attachments.supplier_id', 'suppliers.id');
        $resource   =   $resource->whereIn('suppliers.id', $suppliers);
        $resource   =   $resource->orderBy('supplier_attachments.issued_date', 'asc');
        $resource   =   $resource->groupBy([
            'supplier_attachments.name',
            'supplier_attachments.type',
            'supplier_attachments.issued_date',
            'supplier_attachments.validity_date',
            'supplier_attachments.ref_number',
            'supplier_attachments.place',
        ]);
        $resource   =   $resource->get();

        $group      =   [];

        foreach($resource as $data)
        {
            $group[$data->type] = $data;
        }

        return $this->view('modules.dashboard',[
            'resource' => $group
        ]);
    }

    /**
     * [store description]
     * 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request,
        AttachmentRepository $attachments)
    {

        $validator = $this->validate($request, [
            'type'          => 'required',
            'file'          => 'required',
            'name'          => 'required',
            'issued_date'   => 'required',
            'validity_date' => 'required',
            'ref_number' => 'required',
            'place' => 'required',
        ]);
        $file       = md5_file($request->file);
        $file       = $file.".".$request->file->getClientOriginalExtension();

        $data = [
            // 'supplier_id'   =>  $id,
            'name'          =>  $request->name,
            'place'         =>  $request->place,
            'issued_date'   =>  $request->issued_date,
            'ref_number'    =>  $request->ref_number,
            'type'          =>  $request->type,
            'file_name'     =>  $file,
            'user_id'       =>  \Sentinel::getUser()->id,
            'upload_date'   =>  \Carbon\Carbon::now(),
            'validity_date' =>  $request->validity_date
        ];
        $suppliers = $this->getSuppliers();

        foreach($suppliers as $supplier)
        {
            $data['supplier_id'] = $supplier->id;
            $result     = $attachments->save($data);
        }

        if($result)
        {
            $path       = $request->file->storeAs('supplier-attachments', $file, 'custom');
        }

        return redirect()->back()->with([
            'success'  => "Attachment has been successfully added."
        ]);
    }

    /**
     * [getSuppliers description]
     * 
     * @return [type] [description]
     */
    public function getSuppliers()
    {

        $user       =   \Sentinel::getUser();
        $suppliers  =   json_decode($user->suppliers);
        $resource   =   SupplierEloquent::whereIn('suppliers.id', $suppliers);
        $resource   =   $resource->get();

        return $resource;
    }
}
