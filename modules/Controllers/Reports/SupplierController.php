<?php

namespace Revlv\Controllers\Reports;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Excel;

use \Revlv\Settings\Suppliers\SupplierRepository;
use \Revlv\Settings\Suppliers\SupplierEloquent;
use \Revlv\Settings\Suppliers\Attachments\AttachmentEloquent;

class SupplierController extends Controller
{

    /**
     * [$blankRfq description]
     *
     * @var [type]
     */
    protected $suppliers;


    /**
     * @param model $model
     */
    public function __construct(SupplierEloquent $suppliers)
    {
        $this->suppliers = $suppliers;
        parent::__construct();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $dayAfter = (new \DateTime())->modify('+1 day')->format('Y-m-d');

        $suppliers = $this->suppliers
        ->select([
            'suppliers.name',
            'suppliers.owner',
            // 'supplier_attachments.type',
            // 'supplier_attachments.validity_date',
            \DB::raw(" (select supplier_attachments.validity_date from supplier_attachments where supplier_attachments.supplier_id = suppliers.id AND type = 'dti' order by supplier_attachments.created_at desc limit 1) as dti_validity_date "),
            \DB::raw(" (select supplier_attachments.validity_date from supplier_attachments where supplier_attachments.supplier_id = suppliers.id AND type = 'mayors_permit' order by supplier_attachments.created_at desc limit 1) as mayors_validity_date "),
            \DB::raw(" (select supplier_attachments.validity_date from supplier_attachments where supplier_attachments.supplier_id = suppliers.id AND type = 'tax_clearance' order by supplier_attachments.created_at desc limit 1) as tax_validity_date "),
            \DB::raw(" (select supplier_attachments.validity_date from supplier_attachments where supplier_attachments.supplier_id = suppliers.id AND type = 'philgeps_registraion' order by supplier_attachments.created_at desc limit 1) as philgeps_validity_date ")
        ])
        // ->leftJoin('supplier_attachments', functi    on ($q) {
        //    $q->on('supplier_attachments.supplier_id', '=', 'suppliers.id')
        //      // ->on('supplier_attachments.created_at', '=',
        //        // \DB::raw('(select min(created_at) from supplier_attachments where supplier_id = supplier_attachments.supplier_id)'))
        //      ->where('supplier_attachments.type', '=', 'DTI');
        //  })
        // ->leftJoin('supplier_attachments', 'supplier_attachments.supplier_id', '=', 'suppliers.id')
        // ->whereNull('supplier_attachments.type')
        // ->orWhere('supplier_attachments.validity_date', '<=', $dayAfter)
        ->orderBy('name', 'asc')
        // ->groupBy([
            // 'suppliers.name',
            // 'suppliers.owner',
            // 'supplier_attachments.type',
            // 'supplier_attachments.validity_date',
        // ])
        ->paginate(20);
        return $this->view('modules.reports.supplier.index', [
            'suppliers' =>  $suppliers
        ]);
    }

}
