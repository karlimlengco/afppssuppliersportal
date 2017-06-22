<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;

use \Revlv\Procurements\Canvassing\CanvassingRepository;
use \Revlv\Procurements\Canvassing\CanvassingRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\RFQProponents\RFQProponentRepository;
use \Revlv\Settings\Signatories\SignatoryRepository;

class NoticeOfAwardController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "procurements.noa.";

    /**
     * [$blank description]
     *
     * @var [type]
     */
    protected $blank;
    protected $upr;
    protected $rfq;
    protected $signatories;
    protected $proponents;

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
     * [awardToProponent description]
     *
     * @return [type] [description]
     */
    public function awardToProponent(
        Request $request,
        CanvassingRepository $model,
        RFQProponentRepository $proponents,
        BlankRFQRepository $blank,
        UnitPurchaseRequestRepository $upr,
        $canvasId,
        $proponentId
        )
    {
        $canvasModel        =   $model->findById($canvasId);
        $proponent_model    =   $proponents->with('supplier')->findById($proponentId);
        $supplier_name      =   $proponent_model->supplier->name;

        // Update canvass adjuourned time
        $model->update(['adjourned_time' => \Carbon\Carbon::now()], $canvasId);
        // update proponent adding awarded date
        $proponents->update(['is_awarded' => 1, 'awarded_date' => \Carbon\Carbon::now()], $proponentId);
        // Update rfq
        $rfq    =   $blank->update(['status' => "Awarded To $supplier_name",'is_awarded' => 1, 'awarded_date' => \Carbon\Carbon::now()], $canvasModel->rfq_id);
        // update upr
        $upr->update(['status' => "Awarded To $supplier_name"],  $rfq->upr_id);

        return redirect()->route('procurements.canvassing.show', $canvasId)->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     * [getDatatable description]
     *
     * @return [type]            [description]
     */
    public function getDatatable(CanvassingRepository $model)
    {
        return $model->getNOADatatable();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.procurements.noa.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(BlankRFQRepository $rfq)
    {
        $rfq_list   =   $rfq->lists('id', 'rfq_number');
        $this->view('modules.procurements.canvassing.create',[
            'indexRoute'    =>  $this->baseUrl.'index',
            'rfq_list'      =>  $rfq_list,
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
    public function store(CanvassingRequest $request, CanvassingRepository $model, BlankRFQRepository $rfq)
    {
        $rfq_model              =   $rfq->findById($request->rfq_id);
        $inputs                 =   $request->getData();
        $inputs['rfq_number']   =   $rfq_model->rfq_number;
        $inputs['upr_number']   =   $rfq_model->upr_number;
        $canvass_date           =   $inputs['canvass_date'];

        $rfq->update(['status' => "Canvasing ($canvass_date)"], $rfq_model->id);

        $result = $model->save($inputs);

        return redirect()->route($this->baseUrl.'edit', $result->id)->with([
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
        CanvassingRepository $model,
        $id,
        RFQProponentRepository $proponents,
        SignatoryRepository $signatories,
        UnitPurchaseRequestRepository $upr)
    {
        $result             =   $model->findById($id);
        $proponent_awardee  =   $proponents->with('supplier')->findAwardeeByRFQId($result->rfq_id);

        if(!$proponent_awardee)
        {
            return redirect()->route('procurements.blank-rfq.show', $id)->with([
                'success'    =>  'Awardee is not yet present. Go to canvassing and select proponent.'
            ]);
        }

        $supplier           =   $proponent_awardee->supplier;
        $upr_model          =   $upr->with(['centers','modes','unit','charges','accounts','terms','users'])->findByRFQId($proponent_awardee->rfq_id);

        $signatory_list     =   $signatories->lists('id','name');

        return $this->view('modules.procurements.noa.show',[
            'data'          =>  $result,
            'upr_model'     =>  $upr_model,
            'supplier'      =>  $supplier,
            'signatory_list'=>  $signatory_list,
            'awardee'       =>  $proponent_awardee,
            'printRoute'    =>  $this->baseUrl.'print',
            'indexRoute'    =>  $this->baseUrl.'index',
            'modelConfig'   =>  [
                'receive_award' =>  [
                    'route'     =>  [$this->baseUrl.'update', $result->id]
                ],
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update-signatory', $id],
                    'method'    =>  'PUT'
                ],
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, CanvassingRepository $model, BlankRFQRepository $rfq)
    {
        $result     =   $model->findById($id);
        $rfq_list   =   $rfq->lists('id', 'rfq_number');

        return $this->view('modules.procurements.canvassing.edit',[
            'data'          =>  $result,
            'rfq_list'      =>  $rfq_list,
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
     * [updateSignatory description]
     *
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function updateSignatory(Request $request, $id, CanvassingRepository $model)
    {
        $this->validate($request, [
            'signatory_id'   =>  'required',
        ]);

        $model->update(['signatory_id' =>$request->signatory_id], $id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(
        Request $request,
        $id,
        RFQProponentRepository $rfq,
        BlankRFQRepository $blank,
        CanvassingRepository $model
        )
    {
        $this->validate($request, [
            'received_by'   =>  'required',
            'award_accepted_date'   =>  'required',
        ]);

        $input  =   [
            'received_by'           =>  $request->received_by,
            'award_accepted_date'   =>  $request->award_accepted_date,
        ];

        $result             =   $model->findById($id);
        $proponent_awardee  =   $rfq->with('supplier')->findAwardeeByRFQId($result->rfq_id);

        $proponent          =   $rfq->update($input, $proponent_awardee->id);

        $blank->update(['status' => 'NOA Accepted', 'is_award_accepted' => 1, 'award_accepted_date' => $request->award_accepted_date], $proponent->rfq_id);

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
    public function destroy($id, CanvassingRepository $model)
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
    public function viewPrint(
        $id,
        CanvassingRepository $model,
        BlankRFQRepository $blank,
        UnitPurchaseRequestRepository $upr,
        RFQProponentRepository $rfq)
    {
        $result                     =   $model->with('signatories')->findById($id);
        $proponent_awardee          =   $rfq->with('supplier')->findAwardeeByRFQId($result->rfq_id);
        $rfq_model                  =   $blank->findById($result->rfq_id);
        $upr_model                  =   $upr->with(['unit'])->findById($rfq_model->upr_id);
        $data['supplier']           =   $proponent_awardee->supplier;
        $data['canvass_date']       =   $result->canvass_date;
        $data['rfq_number']         =   $rfq_model->rfq_number;
        $data['transaction_date']   =   $rfq_model->transaction_date;
        $data['total_amount']       =   $upr_model->total_amount;
        $data['unit']               =   $upr_model->unit->description;
        $data['signatory']          =   $result->signatories;

        // $data['transaction_date']   =  $result->transaction_date;
        // $data['venue']              =  $result->venue;
        // $data['quotations']         =  $result->quotations;
        $pdf = PDF::loadView('forms.noa', ['data' => $data])->setOption('margin-left', 13)->setOption('margin-right', 13)->setOption('margin-bottom', 10)->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('noa.pdf');
    }
}
