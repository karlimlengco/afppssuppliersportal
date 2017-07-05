<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF ;

use \Revlv\Procurements\DeliveryOrder\DeliveryOrderRepository;
use \Revlv\Procurements\RFQProponents\RFQProponentRepository;
use \Revlv\Procurements\DeliveryInspection\DeliveryInspectionRepository;
use \Revlv\Procurements\DeliveryInspection\DeliveryInspectionRequest;
use \Revlv\Procurements\NoticeOfAward\NOARepository;
use \Revlv\Procurements\DeliveryInspection\Issues\IssueRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;

class DeliveredInspectionReportController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "procurements.delivered-inspections.";

    /**
     * [$model description]
     *
     * @var [type]
     */
    protected $model;

    /**
     * [$proponents description]
     *
     * @var [type]
     */
    protected $proponents;
    protected $noa;
    protected $signatories;
    protected $inspections;
    protected $delivery;
    protected $issues;
    protected $upr;
    protected $audits;

    /**
     * @param model $model
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * [proceedDelivery description]
     *
     * @param  Request                 $request [description]
     * @param  [type]                  $id      [description]
     * @param  DeliveryOrderRepository $model   [description]
     * @return [type]                           [description]
     */
    public function proceedDelivery(Request $request, $id, DeliveryOrderRepository $model)
    {
        $this->validate($request, [
            'date_delivered_to_coa'   =>  'required',
        ]);

        $input  =   [
            'delivered_to_coa_by'   =>  \Sentinel::getUser()->id,
            'date_delivered_to_coa' =>  $request->date_delivered_to_coa,
        ];

        $result =   $model->update($input, $id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [addIssue description]
     *
     * @param [type]  $id      [description]
     * @param Request $request [description]
     */
    public function addIssue(
        $id,
        Request $request,
        IssueRepository $issues,
        DeliveryInspectionRepository $model)
    {
        $issues->save(['issue' => $request->issue, 'inspection_id' => $id, 'prepared_by' => \Sentinel::getUser()->id], $id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     * [startInspection description]
     *
     * @param [type]  $id      [description]
     * @param Request $request [description]
     */
    public function startInspection($id, Request $request, DeliveryInspectionRepository $model)
    {
        $result     =   $model->update(['start_date' => $request->start_date, 'status' => 'started', 'started_by' => \Sentinel::getUser()->id], $id);

        return redirect()->route($this->baseUrl.'show', $result->id)->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     * [closeInspection description]
     *
     * @param [type]  $id      [description]
     * @param Request $request [description]
     */
    public function closeInspection($id, Request $request, DeliveryInspectionRepository $model)
    {
        $result     =   $model->update(['closed_date' => $request->closed_date, 'status' => 'closed', 'closed_by' => \Sentinel::getUser()->id], $id);

        return redirect()->route($this->baseUrl.'show', $result->id)->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     * [getDatatable description]
     *
     * @return [type]            [description]
     */
    public function getDatatable(DeliveryInspectionRepository $model)
    {
        return $model->getDatatable();
        // return $model->getInspectionDatatable();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.procurements.delivered-inspections.index',[
            'createRoute'   =>  $this->baseUrl."create"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(DeliveryInspectionRepository $model, DeliveryOrderRepository $delivery)
    {
        $delivery_list      =   $delivery->listNotInspected('id', 'rfq_number');

        $this->view('modules.procurements.delivered-inspections.create',[
            'indexRoute'    =>  $this->baseUrl.'index',
            'delivery_list' =>  $delivery_list,
            'modelConfig'   =>  [
                'store' =>  [
                    'route'     =>  $this->baseUrl.'store'
                ]
            ]
        ]);
    }

    /**
     * [store description]
     *
     * @param  DeliveryInspectionRequest    $request  [description]
     * @param  DeliveryOrderRepository      $delivery [description]
     * @param  DeliveryInspectionRepository $model    [description]
     * @return [type]                                 [description]
     */
    public function storeByDR(
            $id,
            DeliveryOrderRepository $delivery,
            UnitPurchaseRequestRepository $upr,
            DeliveryInspectionRepository $model)
    {
        $delivery_model =   $delivery->findById($id);

        $inputs         =   [
            'dr_id'             =>  $id,
            'rfq_id'            =>  $delivery_model->rfq_id,
            'upr_id'            =>  $delivery_model->upr_id,
            'rfq_number'        =>  $delivery_model->rfq_number,
            'upr_number'        =>  $delivery_model->upr_number,
            'delivery_number'   =>  $delivery_model->delivery_number,
            'status'            =>  "pending",
        ];

        $result         =   $model->save($inputs);

        return redirect()->route($this->baseUrl.'show', $result->id)->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     * [store description]
     *
     * @param  DeliveryInspectionRequest    $request  [description]
     * @param  DeliveryOrderRepository      $delivery [description]
     * @param  DeliveryInspectionRepository $model    [description]
     * @return [type]                                 [description]
     */
    public function store(
            DeliveryInspectionRequest $request,
            DeliveryOrderRepository $delivery,
            DeliveryInspectionRepository $model)
    {
        $delivery_model =   $delivery->findById($request->dr_id);

        $inputs         =   [
            'dr_id'             =>  $request->dr_id,
            'rfq_id'            =>  $delivery_model->rfq_id,
            'upr_id'            =>  $delivery_model->upr_id,
            'rfq_number'        =>  $delivery_model->rfq_number,
            'upr_number'        =>  $delivery_model->upr_number,
            'delivery_number'   =>  $delivery_model->delivery_number,
            'status'            =>  "pending",
        ];

        $result         =   $model->save($inputs);

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
        DeliveryOrderRepository $delivery,
        DeliveryInspectionRepository $model,
        NOARepository $noa,
        SignatoryRepository $signatories,
        RFQProponentRepository $proponents)
    {

        $result             =   $model->with('issues')->findById($id);
        $supplier           =   $noa->with('winner')->findByRFQ($result->rfq_id)->winner->supplier;

        $signatory_list     =   $signatories->lists('id','name');

        return $this->view('modules.procurements.delivered-inspections.show',[
            'data'          =>  $result,
            'supplier'      =>  $supplier,
            'signatory_list'=>  $signatory_list,
            'indexRoute'    =>  $this->baseUrl.'index',
            'editRoute'     =>  $this->baseUrl.'edit',
            'printRoute'    =>  $this->baseUrl.'print',
            'modelConfig'   =>  [
                'dtc_proceed' =>  [
                    'route'     =>  [$this->baseUrl.'proceed', $id],
                    'method'    =>  'PUT'
                ],
                'add_issue' =>  [
                    'route'     =>  [$this->baseUrl.'add-issue', $id],
                    'method'    =>  'PUT'
                ],
                'start_inspection' =>  [
                    'route'     =>  [$this->baseUrl.'start-inspection', $id],
                    'method'    =>  'POST'
                ],
                'close_inspection' =>  [
                    'route'     =>  [$this->baseUrl.'close-inspection', $id],
                    'method'    =>  'POST'
                ],
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update-signatory', $id],
                    'method'    =>  'PUT'
                ]
            ]
        ]);
    }

    /**
     * [edit description]
     *
     *
     * @param  [type]                       $id    [description]
     * @param  DeliveryInspectionRepository $model [description]
     * @return [type]                              [description]
     */
    public function edit($id, DeliveryInspectionRepository $model)
    {
        $result   =   $model->findById($id);

        return $this->view('modules.procurements.delivered-inspections.edit',[
            'data'          =>  $result,
            'showRoute'     =>  $this->baseUrl.'show',
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update', $id],
                    'method'    =>  'PUT'
                ]
            ]
        ]);
    }

    /**
     * [update description]
     *
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(Request $request, $id, DeliveryInspectionRepository $model)
    {
        $this->validate($request, [
            'start_date'    => 'required',
            'closed_date'   => 'required',
            'update_remarks'=> 'required',
        ]);

        $data   =   [
            'start_date'    =>  $request->start_date,
            'closed_date'   =>  $request->closed_date,
            'update_remarks'=>  $request->update_remarks,
        ];

        $model->update($data, $id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [updateSignatory description]
     *
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function updateSignatory(Request $request, $id, DeliveryInspectionRepository $model)
    {
        $this->validate($request, [
            'received_by'   => 'required',
            'approved_by'   => 'required',
            'issued_by'     => 'required',
            'requested_by'  => 'required'
        ]);

        $data   =   [
            'received_by'   =>  $request->received_by,
            'approved_by'   =>  $request->approved_by,
            'issued_by'     =>  $request->issued_by,
            'requested_by'  =>  $request->requested_by
        ];

        $model->update($data, $id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
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
        DeliveryInspectionRepository $model,
        NOARepository $noa
        )
    {
        $result                     =   $model->with(['receiver', 'approver','issuer','requestor','upr' ,'delivery'])->findById($id);

        $data['items']              =   $result->delivery->po->items;
        $data['purpose']            =   $result->upr->purpose;
        $data['ref_number']         =   $result->upr->ref_number;
        $data['receiver']           =   $result->receiver;
        $data['approver']           =   $result->approver;
        $data['issuer']             =   $result->issuer;
        $data['requestor']          =   $result->requestor;
        $pdf = PDF::loadView('forms.diir', ['data' => $data])->setOption('margin-bottom', 0)->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('diir.pdf');
    }

    /**
     * [viewLogs description]
     *
     * @param  [type]             $id    [description]
     * @param  BlankRFQRepository $model [description]
     * @return [type]                    [description]
     */
    public function viewLogs($id, DeliveryInspectionRepository $model, AuditLogRepository $logs)
    {
        $modelType  =   'Revlv\Procurements\DeliveryInspection\DeliveryInspectionEloquent';
        $result     =   $logs->findByModelAndId($modelType, $id);
        $data_model =   $model->findById($id);

        return $this->view('modules.procurements.delivered-inspections.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'model'         =>  $data_model
        ]);
    }
}
