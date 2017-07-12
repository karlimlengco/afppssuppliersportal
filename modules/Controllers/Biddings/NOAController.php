<?php

namespace Revlv\Controllers\Biddings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;
use Carbon\Carbon;

use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Biddings\PublicNoa\PublicNOARepository;
use \Revlv\Biddings\PublicNoa\PublicNOARequest;
use \Revlv\Biddings\RequestForBid\RequestForBidRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\Suppliers\SupplierRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\BacSec\BacSecRepository;

class NOAController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "biddings.noa-acceptance.";

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
    public function getDatatable(PublicNOARepository $model)
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
        return $this->view('modules.biddings.noa-acceptance.index',[
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
        $this->view('modules.biddings.noa-acceptance.create',[
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
        PublicNOARequest $request,
        PublicNOARepository $model,
        UnitPurchaseRequestRepository $upr,
        RequestForBidRepository $rfb)
    {
        $rfb_model                  =   $rfb->findById($request->rfb_id);
        $inputs                     =   $request->getData();
        $inputs['upr_number']       =   $rfb_model->upr_number;
        $inputs['upr_id']           =   $rfb_model->upr_id;

        $result = $model->save($inputs);

        $upr->update([
            'status'        => 'NOA Received',
            ], $rfb_model->upr_id);

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
    public function receivedRfb(Request $request, UnitPurchaseRequestRepository $upr, PublicNOARepository $model)
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
        PublicNOARepository $model,
        SupplierRepository $suppliers)
    {
        $supplier_lists =   $suppliers->lists('id', 'name');
        $result         =   $model->findById($id);
        return $this->view('modules.biddings.noa-acceptance.show',[
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
    public function edit($id, PublicNOARepository $model, UnitPurchaseRequestRepository $upr, BacSecRepository $bacsec)
    {
        $result     =   $model->findById($id);
        $upr_list   =   $upr->lists('id', 'upr_number');

        return $this->view('modules.biddings.noa-acceptance.edit',[
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
    public function update(PublicNOARequest $request, $id, PublicNOARepository $model)
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
    public function destroy($id, PublicNOARepository $model)
    {
        $model->delete($id);

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }

}
