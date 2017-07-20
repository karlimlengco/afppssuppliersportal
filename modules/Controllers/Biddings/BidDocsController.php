<?php

namespace Revlv\Controllers\Biddings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;
use Carbon\Carbon;
use Validator;

use Revlv\Biddings\BidDocs\BidDocsRepository;
use Revlv\Biddings\BidDocs\BidDocsRequest;
use Revlv\Biddings\BidOpening\BidOpeningRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\Suppliers\SupplierRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\Holidays\HolidayRepository;

class BidDocsController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "biddings.bid-docs.";

    /**
     * [$upr description]
     *
     * @var [type]
     */
    protected $upr;
    protected $suppliers;
    protected $audits;
    protected $holidays;
    protected $openings;

    /**
     * [$model description]
     *
     * @var [type]
     */
    protected $model;

    /**
     * @param model $model
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * [getDatatable description]
     *
     * @return [type]            [description]
     */
    public function getDatatable(BidDocsRepository $model)
    {
        return $model->getDatatable();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.biddings.bid-docs.index',[
            'createRoute'   =>  $this->baseUrl."create"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        BidDocsRequest $request,
        BidDocsRepository $model,
        SupplierRepository $suppliers,
        HolidayRepository $holidays,
        UnitPurchaseRequestRepository $upr)
    {
        $upr_model  =   $upr->findById($request->upr_id);
        $supplier   =   $suppliers->findById($request->proponent_id);

        $inputs =   [
            'upr_number'        =>  $upr_model->upr_number,
            'ref_number'        =>  $upr_model->ref_number,
            'transaction_date'  =>  $request->bid_transaction_date,
            'proponent_id'      =>  $request->proponent_id,
            'proponent_name'    =>  $supplier->name,
            'upr_id'            =>  $request->upr_id,
            'processed_by'      =>  \Sentinel::getUser()->id,
        ];

        $result = $model->save($inputs);

        return redirect()->back()->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(
        $id,
        $proponent_id,
        BidDocsRepository $model,
        BidOpeningRepository  $openings)
    {
        $proponent  =   $model->findById($proponent_id);
        $opens      =   $openings->findById($id);

        return $this->view('modules.biddings.proponent.create',[
            'id'        =>  $id,
            'proponent' =>  $proponent,
            'modelConfig'       =>  [
                'update'   => [
                    'route' => [$this->baseUrl.'update', $proponent_id],
                    'method'=> 'PUT'
                ]
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,
        BidDocsRepository $model,
        UnitPurchaseRequestRepository $upr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(
        $id,
        Request $request,
        BidDocsRepository $model)
    {
        $this->validate($request, [
            'bid_amount'    =>  'required'
        ]);
        $model->update(['bid_amount' => $request->bid_amount, 'is_lcb' => $request->is_lcb, 'is_scb' => $request->is_scb], $id);

        return redirect()->back()->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, BidDocsRepository $model)
    {
        $model->delete($id);

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }

}