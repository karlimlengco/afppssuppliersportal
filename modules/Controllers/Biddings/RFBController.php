<?php

namespace Revlv\Controllers\Biddings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;
use Carbon\Carbon;

use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Biddings\RequestForBid\RequestForBidRepository;
use \Revlv\Biddings\RequestForBid\RequestForBidRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\Suppliers\SupplierRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\BacSec\BacSecRepository;

class RFBController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "biddings.request-for-bids.";

    /**
     * [$upr description]
     *
     * @var [type]
     */
    protected $upr;
    protected $suppliers;
    protected $signatories;
    protected $audits;
    protected $bacsec;

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
    public function getDatatable(RequestForBidRepository $model)
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
        return $this->view('modules.biddings.rfb.index',[
            'createRoute'   =>  $this->baseUrl."create"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(UnitPurchaseRequestRepository $upr)
    {
        $upr_list   =   $upr->listPending('id', 'upr_number');
        $this->view('modules.biddings.rfb.create',[
            'indexRoute'    =>  $this->baseUrl.'index',
            'upr_list'      =>  $upr_list,
            'modelConfig'   =>  [
                'store' =>  [
                    'route'     =>  $this->baseUrl.'store'
                ]
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        RequestForBidRequest $request,
        RequestForBidRepository $model,
        UnitPurchaseRequestRepository $upr)
    {
        $upr_model                  =   $upr->findById($request->upr_id);
        $inputs                     =   $request->getData();
        $inputs['upr_number']       =   $upr_model->upr_number;
        $inputs['transaction_date'] =   $request->rfb_transaction_date;
        $inputs['processed_by']     =   \Sentinel::getUser()->id;
        $inputs['status']           =   'Released';
        $split_upr                  =   explode('-', $upr_model->ref_number);
        $inputs['rfb_number']       =  "RFB-".$split_upr[1]."-".$split_upr[2]."-".$split_upr[3]."-".$split_upr[4] ;

        $prepared_date              =   Carbon::createFromFormat('Y-m-d', $upr_model->date_prepared);
        $transaction_date           =   Carbon::createFromFormat('Y-m-d', $request->rfb_transaction_date);
        $days                       =   $prepared_date->diffInDays($transaction_date, false);

        $inputs['days']         =   $days;

        $result = $model->save($inputs);

        $upr->update([
            'status'        => 'Released Request For Bid',
            'state'         => 'On-Going',
            'date_processed'=> \Carbon\Carbon::now(),
            'processed_by'  => \Sentinel::getUser()->id
            ], $upr_model->id);

        return redirect()->route($this->baseUrl.'show', $result->id)->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     * [receivedRfb description]
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function receivedRfb(Request $request, UnitPurchaseRequestRepository $upr, RequestForBidRepository $model)
    {
        $this->validate($request,[
            'received_date' =>  'required',
            'id'            =>  'required',
        ]);

        $result =   $model->update(['received_date' => $request->received_date, 'status' => 'Received'], $request->id);

        $upr->update([
            'status'        => 'Received Request For Bid',
            ], $result->id);


        return redirect()->route($this->baseUrl.'show', $result->id)->with([
            'success'  => "Record has been successfully updated."
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
        SignatoryRepository $signatories,
        RequestForBidRepository $model,
        SupplierRepository $suppliers)
    {
        $supplier_lists=   $suppliers->lists('id', 'name');
        $result         =   $model->findById($id);
        return $this->view('modules.biddings.rfb.show',[
            'data'              =>  $result,
            'supplier_lists'    =>  $supplier_lists,
            'indexRoute'        =>  $this->baseUrl.'index',
            'editRoute'         =>  $this->baseUrl.'edit',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, RequestForBidRepository $model, UnitPurchaseRequestRepository $upr, BacSecRepository $bacsec)
    {
        $result     =   $model->findById($id);
        $upr_list   =   $upr->lists('id', 'upr_number');

        return $this->view('modules.biddings.rfb.edit',[
            'data'          =>  $result,
            'bacsec_list'   =>  $bacsec->lists('id', 'name'),
            'upr_list'      =>  $upr_list,
            'indexRoute'    =>  $this->baseUrl.'show',
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update', $id],
                    'method'    =>  'PUT'
                ],
                'destroy'   => [
                    'route' => [$this->baseUrl.'destroy',$id],
                    'method'=> 'DELETE'
                ]
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestForBidRequest $request, $id, RequestForBidRepository $model)
    {
        $model->update($request->getData(), $id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, RequestForBidRepository $model)
    {
        $model->delete($id);

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }

}
