<?php

namespace Revlv\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use Excel;
use Sentinel;

use \Revlv\Settings\CateredUnits\CateredUnitRepository;
use \Revlv\Settings\CateredUnits\Attachments\AttachmentEloquent;
use \Revlv\Settings\CateredUnits\Attachments\AttachmentRepository;

class AppController extends Controller
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
    public function __construct(CateredUnitRepository $model)
    {
        $this->model  =   $model;
        parent::__construct();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CateredUnitRepository $model)
    {

        $user       =   \Sentinel::getUser();
        $unit       =   AttachmentEloquent::where('unit_id', $user->unit_id)->paginate(10);

        return $this->view('modules.app',[
            'resources' => $unit,
            'unit_id'   => $user->unit_id
        ]);
    }

    /**
     * [upload description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function upload(Request $request, $id, AttachmentRepository $attachments)
    {
        $validator = $this->validate($request, [
            'file'          => 'required',
            'name'          => 'required',
            'validity_date' => 'required',
            'amount'        => 'required',
        ]);
        $file       = md5_file($request->file);
        $file       = $file.".".$request->file->getClientOriginalExtension();


        $result     = $attachments->save([
            'unit_id'       =>  $id,
            'name'          =>  $request->name,
            'amount'          =>  $request->amount,
            'validity_date'          =>  $request->validity_date,
            'file_name'     =>  $file,
            'user_id'       =>  \Sentinel::getUser()->id,
            'upload_date'   =>  \Carbon\Carbon::now()
        ]);

        if($result)
        {
            $path       = $request->file->storeAs('unit-attachments', $file, 'custom');
        }

        return redirect()->back()->with([
            'success'  => "Attachment has been successfully added."
        ]);

    }

    /**
     * [download description]
     * @return [type] [description]
     */
    public function download($id, AttachmentRepository $attachments)
    {
        $result     = $attachments->findById($id);

        $directory      =   '/storage/Sites/epmis/storage/app/unit-attachments/'.$result->file_name;
        if(!file_exists($directory))
        {
            return 'Sorry. File does not exists.';
        }
        return response()->download($directory);
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
