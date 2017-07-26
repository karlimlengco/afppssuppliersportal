<?php

namespace Revlv\Controllers\Biddings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF ;
use Carbon\Carbon;
use Validator;
use \App\Support\Breadcrumb;

use \Revlv\Procurements\DeliveryOrder\DeliveryOrderRepository;
use \Revlv\Procurements\RFQProponents\RFQProponentRepository;
use \Revlv\Procurements\DeliveryInspection\DeliveryInspectionRepository;
use \Revlv\Procurements\DeliveryInspection\DeliveryInspectionRequest;
use \Revlv\Procurements\NoticeOfAward\NOARepository;
use \Revlv\Procurements\DeliveryInspection\Issues\IssueRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\Holidays\HolidayRepository;

class DeliveredInspectionReportController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "biddings.delivered-inspections.";

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
    protected $holidays;

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
     * [correctedIssue description]
     *
     * @param  [type]          $id     [description]
     * @param  IssueRepository $issues [description]
     * @return [type]                  [description]
     */
    public function correctedIssue($id, IssueRepository $issues, Request $request)
    {
        $result =   $issues->update(['remarks' => $request->remarks, 'is_corrected' => 1], $id);

        return redirect()->route($this->baseUrl.'show', $result->inspection_id)->with([
            'success'  => "New record has been successfully updated."
        ]);
    }

    /**
     * [startInspection description]
     *
     * @param [type]  $id      [description]
     * @param Request $request [description]
     */
    public function startInspection(
        $id,
        Request $request,
        DeliveryInspectionRepository $model,
        UnitPurchaseRequestRepository $upr,
        HolidayRepository $holidays
        )
    {
        $diir                   =   $model->findById($id);
        $tiac                   =   $diir->delivery->inspections;

        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('start_date') );
        $tiac_date              =   Carbon::createFromFormat('Y-m-d', $tiac->accepted_date );

        $day_delayed            =   $tiac_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $validator = Validator::make($request->all(),[
            'start_date'       => 'required',
            'action'            => 'required_with:remarks'
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('remarks') == null && $day_delayed > 1) {
                $validator->errors()->add('remarks', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Your process is delay. Please add remarks to continue.'])
                        ->withErrors($validator)
                        ->withInput();
        }
        // Delay
        $inputs     =   [
            'start_date'    => $request->start_date,
            'status'        => 'started',
            'started_by'    => \Sentinel::getUser()->id,
            'days'          =>  $day_delayed,
            'action'       =>  $request->action,
            'remarks'       =>  $request->remarks
        ];

        $result     =   $model->update($inputs, $id);

        $upr->update([
            'status' => 'DIIR Started',
            'delay_count'   => ($day_delayed > 1 )? $day_delayed - 1 : 0,
            'calendar_days' => $day_delayed + $result->upr->calendar_days,
            'action'        => $request->action,
            'remarks'       => $request->remarks
            ], $result->upr_id);

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
    public function closeInspection(
        $id,
        Request $request,
        DeliveryInspectionRepository $model,
        UnitPurchaseRequestRepository $upr,
        HolidayRepository $holidays)
    {
        $diir                   =   $model->findById($id);

        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('closed_date') );
        $diir_date              =   Carbon::createFromFormat('Y-m-d', $diir->start_date );

        $day_delayed            =   $diir_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $validator = Validator::make($request->all(),[
            'closed_date'       => 'required',
            'close_action'       => 'required_with:close_remarks',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('close_remarks') == null && $day_delayed > 1) {
                $validator->errors()->add('close_remarks', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Your process is delay. Please add remarks to continue.'])
                        ->withErrors($validator)
                        ->withInput();
        }
        // Delay

        $inputs     =   [
            'closed_date'   => $request->closed_date,
            'status'        => 'closed',
            'closed_by'     => \Sentinel::getUser()->id,
            'close_days'    =>  $day_delayed,
            'close_remarks' =>  $request->close_remarks,
            'close_action' =>  $request->close_action
        ];

        $result     =   $model->update($inputs, $id);

        $upr->update([

            'status' => 'DIIR Closed',
            'delay_count'   => ($day_delayed > 1 )? $day_delayed - 1 : 0,
            'calendar_days' => $day_delayed + $result->upr->calendar_days,
            'action'        => $request->action,
            'remarks'       => $request->remarks
            ], $result->upr_id);

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
        return $model->getDatatable('bidding');
        // return $model->getInspectionDatatable();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.biddings.delivered-inspections.index',[
            'createRoute'   =>  $this->baseUrl."create",
            'breadcrumbs' => [
                new Breadcrumb('Public Biddings'),
                new Breadcrumb('DIIR', 'biddings.delivered-inspections.index')
            ]
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

        $this->view('modules.biddings.delivered-inspections.create',[
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

        $upr->update([
            'status' => "DIIR Created",
            ], $delivery_model->upr_id);

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

        return $this->view('modules.biddings.delivered-inspections.show',[
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
            ],
            'breadcrumbs' => [
                new Breadcrumb('Public Biddings'),
                new Breadcrumb($result->upr_number, 'biddings.unit-purchase-requests.show', $result->upr_id),
                new Breadcrumb('DIIR', 'biddings.delivered-inspections.index')
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

        return $this->view('modules.biddings.delivered-inspections.edit',[
            'data'          =>  $result,
            'showRoute'     =>  $this->baseUrl.'show',
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update', $id],
                    'method'    =>  'PUT'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb('DIIR', 'biddings.delivered-inspections.show',$result->id),
                new Breadcrumb('Update'),
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
            'inspected_by'  => 'required'
        ]);

        $data   =   [
            'received_by'   =>  $request->received_by,
            'approved_by'   =>  $request->approved_by,
            'issued_by'     =>  $request->issued_by,
            'requested_by'  =>  $request->requested_by
            'inspected_by'  =>  $request->inspected_by
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
        $pdf = PDF::loadView('forms.diir', ['data' => $data])
            ->setOption('margin-bottom', 30)
            ->setOption('footer-html', route('pdf.footer'))
            ->setPaper('a4');

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

        return $this->view('modules.biddings.delivered-inspections.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'model'         =>  $data_model
        ]);
    }
}
