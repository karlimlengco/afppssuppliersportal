<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;

use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Procurements\BlankRequestForQuotation\UpdateRequest;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\Suppliers\SupplierRepository;

class BlankRFQController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "procurements.blank-rfq.";

    /**
     * [$upr description]
     *
     * @var [type]
     */
    protected $upr;
    protected $suppliers;
    protected $signatories;

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
    public function getDatatable(BlankRFQRepository $model)
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
        return $this->view('modules.procurements.blank-rfq.index',[
            'createRoute'   =>  $this->baseUrl."create"
        ]);
    }

    /**
     * [getInfo description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getInfo($id, BlankRFQRepository $rfq)
    {
        $result =   $rfq->getInfo($id);
        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(UnitPurchaseRequestRepository $upr)
    {
        $upr_list   =   $upr->listPending('id', 'upr_number');
        $this->view('modules.procurements.blank-rfq.create',[
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
        BlankRFQRequest $request,
        BlankRFQRepository $model,
        UnitPurchaseRequestRepository $upr)
    {
        $upr_model              =   $upr->findById($request->upr_id);
        $inputs                 =   $request->getData();
        $inputs['upr_number']   =   $upr_model->upr_number;
        $inputs['processed_by'] =   \Sentinel::getUser()->id;
        $split_upr              =   explode('-', $upr_model->ref_number);
        $inputs['rfq_number']   =  "RFQ-".$split_upr[1]."-".$split_upr[2]."-".$split_upr[3]."-".$split_upr[4] ;

        $result = $model->save($inputs);

        $upr->update([
            'status'        => 'Processing RFQ',
            'state'         => 'On-Going',
            'date_processed'=> \Carbon\Carbon::now(),
            'processed_by'  => \Sentinel::getUser()->id
            ], $upr_model->id);

        return redirect()->route($this->baseUrl.'show', $result->id)->with([
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
        SignatoryRepository $signatories,
        BlankRFQRepository $model,
        SupplierRepository $suppliers)
    {
        $supplier_lists =   $suppliers->lists('id', 'name');
        $signatory_lists=   $signatories->lists('id', 'name');
        $result         =   $model->with(['invitations', 'proponents','upr', 'canvassing'])->findById($id);

        $exist_supplier =   $result->proponents->pluck('proponents')->toArray();
        foreach($exist_supplier as $list)
        {
            unset($supplier_lists[$list]);
        }

        return $this->view('modules.procurements.blank-rfq.show',[
            'supplier_lists'    =>  $supplier_lists,
            'data'              =>  $result,
            'signatory_lists'   =>  $signatory_lists,
            'indexRoute'        =>  $this->baseUrl.'index',
            'printRoute'        =>  $this->baseUrl.'print',
            'editRoute'         =>  $this->baseUrl.'edit',
            'modelConfig'       =>  [
                'add_proponents'   => [
                    'route' => 'procurements.rfq-proponents.store',
                    'method'=> 'DELETE'
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
    public function edit($id, BlankRFQRepository $model, UnitPurchaseRequestRepository $upr)
    {
        $result     =   $model->findById($id);
        $upr_list   =   $upr->lists('id', 'upr_number');

        return $this->view('modules.procurements.blank-rfq.edit',[
            'data'          =>  $result,
            'upr_list'      =>  $upr_list,
            'indexRoute'    =>  $this->baseUrl.'index',
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
    public function update(UpdateRequest $request, $id, BlankRFQRepository $model)
    {
        $model->update($request->getData(), $id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [closed description]
     *
     * @param  [type]             $id    [description]
     * @param  BlankRFQRepository $model [description]
     * @return [type]                    [description]
     */
    public function closed($id, BlankRFQRepository $model)
    {

        $model->update(['status' => 'closed', 'completed_at' => \Carbon\Carbon::now()], $id);

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
    public function destroy($id, BlankRFQRepository $model)
    {
        $model->delete($id);

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }

    /**
     * [viewPrint description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrint($id, BlankRFQRepository $model)
    {
        $result     =   $model->with(['upr', 'items'])->findById($id);
        $data['total_amount']       =  $result->upr->total_amount;
        $data['transaction_date']   =  $result->transaction_date;
        $data['rfq_number']         =  $result->rfq_number;
        $data['upr_number']         =  $result->upr_number;
        $data['opening_time']       =  $result->opening_time;
        $data['deadline']           =  $result->deadline;
        $data['items']              =  $result->items;
        // dd();
        $pdf = PDF::loadView('forms.rfq', ['data' => $data])->setOption('margin-bottom', 0)->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('rfq.pdf');
        return $pdf->download('rfq.pdf');
    }
}
