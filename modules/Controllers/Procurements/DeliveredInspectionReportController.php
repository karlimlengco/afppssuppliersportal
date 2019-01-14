<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF ;
use Carbon\Carbon;
use Validator;
use \App\Support\Breadcrumb;
use App\Events\Event;

use \Revlv\Settings\Forms\RIS\RISRepository;
use \Revlv\Settings\Forms\RIS2\RIS2Repository;
use \Revlv\Settings\Forms\RSMI\RSMIRepository;
use \Revlv\Settings\Forms\RAR\RARRepository;
use \Revlv\Settings\Forms\COI\COIRepository;
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
use \Revlv\Users\Logs\UserLogRepository;
use \Revlv\Users\UserRepository;
use \Revlv\Settings\Forms\Header\HeaderRepository;

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
    protected $headers;
    protected $upr;
    protected $audits;
    protected $holidays;
    protected $users;
    protected $userLogs;
    protected $ris;
    protected $ris2Form;
    protected $rsmiForm;
    protected $rarForm;
    protected $coiForm;

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
        $this->validate($request,[
          'start_date'  =>  'required'
        ]);
        $diir                   =   $model->findById($id);
        $tiac                   =   $diir->delivery->inspections;

        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('start_date') );
        $tiac_date              =   Carbon::createFromFormat('!Y-m-d', $tiac->accepted_date );
        $cd                     =   $tiac_date->diffInDays($transaction_date);

        $day_delayed            =   $tiac_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;


        if($day_delayed > 1)
        {
            $day_delayed = $day_delayed - 1;
        }

        $validator = Validator::make($request->all(),[
            'start_date'       => 'required|after_or_equal:'. $tiac_date->format('Y-m-d'),
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('remarks') == null && $day_delayed > 1) {
                $validator->errors()->add('remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('action') == null && $day_delayed > 1) {
                $validator->errors()->add('action', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Please Check Your Fields.'])
                        ->withErrors($validator)
                        ->withInput();
        }
        // Delay
        $inputs     =   [
            'start_date'    => $request->start_date,
            'status'        => 'started',
            'started_by'    => \Sentinel::getUser()->id,
            'days'          =>  $wd,
            'action'       =>  $request->action,
            'remarks'       =>  $request->remarks
        ];

        $result     =   $model->update($inputs, $id);

        $upr_result = $upr->update([
            'next_allowable'=> 1,
            'next_step'     => 'Close Inspection',
            'next_due'      => $tiac_date->addDays(1),
            'last_date'     => $transaction_date,
            'status'        => 'DIIR Started',
            'delay_count'   => $day_delayed,
            'calendar_days' => $cd + $result->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr_id);

        event(new Event($upr_result, $upr_result->upr_number." DIIR Started"));

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
        $this->validate($request,[
          'closed_date'  =>  'required'
        ]);
        $diir                   =   $model->findById($id);
        $tiac                   =   $diir->delivery->inspections;

        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('closed_date') );
        $diir_date              =   Carbon::createFromFormat('!Y-m-d', $diir->start_date );
        $cd                     =   $diir_date->diffInDays($transaction_date);

        $day_delayed            =   $diir_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;
        if($day_delayed > 1)
        {
            $day_delayed = $day_delayed - 1;
        }


        $validator = Validator::make($request->all(),[
            'closed_date'       => 'required|after_or_equal:'. $diir_date->format('Y-m-d'),
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('close_remarks') == null && $day_delayed > 1) {
                $validator->errors()->add('close_remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('close_action') == null && $day_delayed > 1) {
                $validator->errors()->add('close_action', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Please Check Your Fields.'])
                        ->withErrors($validator)
                        ->withInput();
        }
        // Delay

        $inputs     =   [
            'closed_date'   => $request->closed_date,
            'status'        => 'closed',
            'closed_by'     => \Sentinel::getUser()->id,
            'close_days'    =>  $wd,
            'close_remarks' =>  $request->close_remarks,
            'close_action' =>  $request->close_action
        ];

        $result     =   $model->update($inputs, $id);

        $upr_result  =  $upr->update([
            'next_allowable'=> 1,
            'next_step'     => 'Prepare Voucher',
            'next_due'      => $transaction_date->addDays(1),
            'last_date'     => $transaction_date,
            'status' => 'DIIR Closed',
            'delay_count'   => $day_delayed,
            'calendar_days' => $cd + $result->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr_id);


        event(new Event($upr_result, $upr_result->upr_number." DIIR Closed"));

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
    public function index(Request $request, DeliveryInspectionRepository $model)
    {
        return $this->view('modules.procurements.delivered-inspections.index',[
            'resources'     =>  $model->paginateByRequest(10, $request, 'alternative'),
            'createRoute'   =>  $this->baseUrl."create",
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('DIIR', 'procurements.delivered-inspections.index')
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

        $upr_result =  $upr->update([
            'status' => "Delivered Items and Inspection Report",
            ], $delivery_model->upr_id);


        event(new Event($upr_result, $upr_result->upr_number." Delivered Items and Inspection Report"));

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
        // $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        }

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
            ],
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb($result->upr_number, 'procurements.unit-purchase-requests.show', $result->upr_id),
                new Breadcrumb('DIIR', 'procurements.delivered-inspections.index')
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
    public function edit($id, DeliveryInspectionRepository $model, SignatoryRepository $signatories)
    {
        $result   =   $model->findById($id);
        $signatory_list     =   $signatories->lists('id','name');

        return $this->view('modules.procurements.delivered-inspections.edit',[
            'data'          =>  $result,
            'signatory_list'=>  $signatory_list,
            'showRoute'     =>  $this->baseUrl.'show',
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update', $id],
                    'method'    =>  'PUT',
                    'novalidate'=>  'novalidate'
                ],
                'destroy'   => [
                    'route' => [$this->baseUrl.'destroy',$id],
                    'method'=> 'DELETE',
                    'novalidate'=>  'novalidate'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('DIIR', 'procurements.delivered-inspections.show',$result->id),
                new Breadcrumb('Update'),
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, DeliveryOrderRepository $model)
    {
        $model->delete($id);

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }

    /**
     * [update description]
     *
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function update(
        Request $request,
        $id,
        UnitPurchaseRequestRepository $upr,
        AuditLogRepository $audits,
        HolidayRepository $holidays,
        UserLogRepository $userLogs,
        SignatoryRepository $signatories,
        UserRepository $users,
        DeliveryInspectionRepository $model)
    {
        $diir_model =   $model->findById($id);
        $request->session()->flash('error-msg', 'Please Check Field');
        $this->validate($request, [
            'received_by'       => 'required',
            'approved_by'       => 'required',
            'inspected_by'      => 'required',
            'issued_by'         => 'required',
            'chairman_signatory'=> 'required',
            'signatory_two'     => 'required',
            'signatory_one'     => 'required',
            'requested_by'      => 'required'
        ]);

        $data   =   [
            'start_date'        =>  $request->start_date,
            'closed_date'       =>  $request->closed_date,
            'received_by'       =>  $request->received_by,
            'approved_by'       =>  $request->approved_by,
            'inspected_by'      =>  $request->inspected_by,
            'issued_by'         =>  $request->issued_by,
            'requested_by'      =>  $request->requested_by,
            'chairman_signatory'=>  $request->chairman_signatory,
            'signatory_two'     =>  $request->signatory_two,
            'signatory_one'     =>  $request->signatory_one,
            'remarks'     =>  $request->remarks,
            'action'     =>  $request->action,
            'close_remarks'     =>  $request->close_remarks,
            'close_action'     =>  $request->close_action,
        ];

        if($diir_model->received_by != $request->received_by)
        {
            $receiver  =   $signatories->findById($request->received_by);
            $data['received_signatory']   =   $receiver->name."/".$receiver->ranks."/".$receiver->branch."/".$receiver->designation;
        }

        if($diir_model->approved_by != $request->approved_by)
        {
            $approver  =   $signatories->findById($request->approved_by);
            $data['approved_signatory']   =   $approver->name."/".$approver->ranks."/".$approver->branch."/".$approver->designation;
        }

        if($diir_model->inspected_by != $request->inspected_by)
        {
            $inpector  =   $signatories->findById($request->inspected_by);
            $data['inspected_signatory']   =   $inpector->name."/".$inpector->ranks."/".$inpector->branch."/".$inpector->designation;
        }

        if($diir_model->issued_by != $request->issued_by)
        {
            $issuer  =   $signatories->findById($request->issued_by);
            $data['issued_signatory']   =   $issuer->name."/".$issuer->ranks."/".$issuer->branch."/".$issuer->designation;
        }

        if($diir_model->requested_by != $request->requested_by)
        {
            $requestor  =   $signatories->findById($request->requested_by);
            $data['requested_signatory']   =   $requestor->name."/".$requestor->ranks."/".$requestor->branch."/".$requestor->designation;
        }

        if($diir_model->chairman_signatory != $request->chairman_signatory)
        {
            $requestor  =   $signatories->findById($request->chairman_signatory);
            $data['chairman_signatory_name']   =   $requestor->name."/".$requestor->ranks."/".$requestor->branch."/".$requestor->designation;
        }

        if($diir_model->signatory_one != $request->signatory_one)
        {
            $requestor  =   $signatories->findById($request->signatory_one);
            $data['signatory_one_name']   =   $requestor->name."/".$requestor->ranks."/".$requestor->branch."/".$requestor->designation;
        }

        if($diir_model->signatory_two != $request->signatory_two)
        {
            $requestor  =   $signatories->findById($request->signatory_two);
            $data['signatory_two_name']   =   $requestor->name."/".$requestor->ranks."/".$requestor->branch."/".$requestor->designation;
        }

        if($diir_model->signatory_three != $request->signatory_three)
        {
            $requestor  =   $signatories->findById($request->signatory_three);
            $data['signatory_three_name']   =   $requestor->name."/".$requestor->ranks."/".$requestor->branch."/".$requestor->designation;
        }

        if($diir_model->signatory_four != $request->signatory_four)
        {
            $requestor  =   $signatories->findById($request->signatory_four);
            $data['signatory_four_name']   =   $requestor->name."/".$requestor->ranks."/".$requestor->branch."/".$requestor->designation;
        }


        if($diir_model->signatory_five != $request->signatory_five)
        {
            $requestor  =   $signatories->findById($request->signatory_five);
            $data['signatory_five_name']   =   $requestor->name."/".$requestor->ranks."/".$requestor->branch."/".$requestor->designation;
        }

        $result =   $model->update($data, $id);
        $tiac                   =   $result->delivery->inspections;

        $tiac_date              =   Carbon::createFromFormat('!Y-m-d', $tiac->accepted_date );

        $holiday_lists          =   $holidays->lists('id','holiday_date');
        if($request->start_date != null && $request->start_date != $diir_model->start_date)
        {

            $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('start_date') );
            $day_delayed            =   $tiac_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
                return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
            }, $transaction_date);
            $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;


            if($day_delayed > 1)
            {
                $day_delayed = $day_delayed - 1;
            }

            if($wd != $result->days)
            {
                $model->update(['days' => $wd], $id);
            }

        }
        if($request->closed_date != null && $request->closed_date != $diir_model->closed_date)
        {

            $holiday_lists          =   $holidays->lists('id','holiday_date');
            $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('closed_date') );
            $diir_date              =   Carbon::createFromFormat('!Y-m-d', $tiac->accepted_date );
            $cd                     =   $diir_date->diffInDays($transaction_date);

            $day_delayed            =   $diir_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
                return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
            }, $transaction_date);
            $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

            if($day_delayed > 1)
            {
                $day_delayed = $day_delayed - 1;
            }

            if($wd != $result->days)
            {
                $model->update(['close_days' => $wd], $id);
            }

        }

        $modelType  =   'Revlv\Procurements\DeliveryInspection\DeliveryInspectionEloquent';
        $resultLog  =   $audits->findLastByModelAndId($modelType, $id);

        $userAdmins =   $users->getAllAdmins();

        foreach($userAdmins as $admin)
        {
            if($admin->hasRole('Admin'))
            {
                $data   =   ['audit_id' => $resultLog->id, 'admin_id' => $admin->id];
                $x = $userLogs->save($data);
            }
        }

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
            'inspected_by'  => 'required',
            'issued_by'     => 'required',
            'requested_by'  => 'required'
        ]);

        $data   =   [
            'received_by'   =>  $request->received_by,
            'inspected_by'  =>  $request->inspected_by,
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
        NOARepository $noa, HeaderRepository $headers
        )
    {
        $result                     =   $model->with(['receiver', 'approver','inspector','issuer','requestor','upr' ,'delivery'])->findById($id);
        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        }

        $header                     =  $headers->findByUnit($result->upr->units);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $data['items']              =   $result->delivery->po->items;
        $data['delivery_number']              =   $result->delivery->delivery_number;
        $data['delivery_date']              =   $result->delivery->delivery_date;
        $data['purpose']            =   $result->upr->purpose;
        $data['place']             =   $result->upr->place_of_delivery;
        $data['centers']            =   $result->upr->centers->name;
        $data['units']              =   $result->upr->unit->short_code;
        $data['ref_number']         =   $result->upr->ref_number;
        $data['supplier']           =   $supplier;
        $data['date']               =   $result->delivery->delivery_date;
        $data['po_number']          =   $result->delivery->po->po_number;
        $data['po_date']            =   $result->delivery->po->coa_approved_date;
        $data['invoice']            =   $result->delivery->inspections->invoices;
        $data['issues']             =   $result->delivery->diir->issues;
        $data['header']             =   $result->upr->centers;

        $data['receiver']           =   explode('/',$result->received_signatory);
        $data['inspector']          =   explode('/',$result->inspected_signatory);
        $data['approver']           =   explode('/',$result->approved_signatory);
        $data['issuer']             =   explode('/',$result->issued_signatory);
        $data['requestor']          =   explode('/',$result->requested_signatory);
        // dd($data);
        $pdf = PDF::loadView('forms.new-diir', ['data' => $data])
            ->setOption('margin-bottom', 30);
            // ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('diir.pdf');
    }

    public function viewPrintDIIR2(
        $id,
        DeliveryInspectionRepository $model,
        NOARepository $noa, HeaderRepository $headers
        )
    {
        $result                     =   $model->with(['receiver', 'approver','inspector','issuer','requestor','upr' ,'delivery'])->findById($id);
        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        }

        $header                     =  $headers->findByUnit($result->upr->units);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $data['items']              =   $result->delivery->po->items;
        $data['delivery_number']              =   $result->delivery->delivery_number;
        $data['delivery_date']              =   $result->delivery->delivery_date;
        $data['purpose']            =   $result->upr->purpose;
        $data['place']             =   $result->upr->place_of_delivery;
        $data['centers']            =   $result->upr->centers->name;
        $data['units']              =   $result->upr->unit->short_code;
        $data['ref_number']         =   $result->upr->ref_number;
        $data['supplier']           =   $supplier;
        $data['date']               =   $result->delivery->delivery_date;
        $data['po_number']          =   $result->delivery->po->po_number;
        $data['po_date']            =   $result->delivery->po->coa_approved_date;
        $data['invoice']            =   $result->delivery->inspections->invoices;
        $data['issues']             =   $result->delivery->diir->issues;
        $data['header']             =   $result->upr->centers;

        $data['receiver']           =   explode('/',$result->received_signatory);
        $data['inspector']          =   explode('/',$result->inspected_signatory);
        $data['approver']           =   explode('/',$result->approved_signatory);
        $data['issuer']             =   explode('/',$result->issued_signatory);
        $data['requestor']          =   explode('/',$result->requested_signatory);
        // dd($data);
        $pdf = PDF::loadView('forms.diir3', ['data' => $data])
            ->setOption('margin-bottom', 30);
            // ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('diir.pdf');
    
    }

    public function viewPrintPreRepair(
        $id,
        DeliveryInspectionRepository $model,
        NOARepository $noa, HeaderRepository $headers
        )
    {
        $result                     =   $model->with(['receiver', 'approver','inspector','issuer','requestor','upr' ,'delivery'])->findById($id);
        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        }

        $header                     =  $headers->findByUnit($result->upr->units);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $data['items']              =   $result->delivery->po->items;
        $data['delivery_number']              =   $result->delivery->delivery_number;
        $data['delivery_date']              =   $result->delivery->delivery_date;
        $data['purpose']            =   $result->upr->purpose;
        $data['place']             =   $result->upr->place_of_delivery;
        $data['centers']            =   $result->upr->centers->name;
        $data['units']              =   $result->upr->unit->short_code;
        $data['ref_number']         =   $result->upr->ref_number;
        $data['supplier']           =   $supplier;
        $data['date']               =   $result->delivery->delivery_date;
        $data['po_number']          =   $result->delivery->po->po_number;
        $data['po_date']            =   $result->delivery->po->coa_approved_date;
        $data['invoice']            =   $result->delivery->inspections->invoices;
        $data['issues']             =   $result->delivery->diir->issues;
        $data['header']             =   $result->upr->centers;

        $data['receiver']           =   explode('/',$result->received_signatory);
        $data['inspector']          =   explode('/',$result->inspected_signatory);
        $data['approver']           =   explode('/',$result->approved_signatory);
        $data['issuer']             =   explode('/',$result->issued_signatory);
        $data['requestor']          =   explode('/',$result->requested_signatory);
        // dd($data);
        $pdf = PDF::loadView('forms.pre-repair', ['data' => $data])
            ->setOption('margin-bottom', 30);
            // ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('diir.pdf');
    
    }

    public function viewPrintPostRepair(
        $id,
        DeliveryInspectionRepository $model,
        NOARepository $noa, HeaderRepository $headers)
    {
        $result                     =   $model->with(['receiver', 'approver','inspector','issuer','requestor','upr' ,'delivery'])->findById($id);
        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        }

        $header                     =  $headers->findByUnit($result->upr->units);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $data['items']              =   $result->delivery->po->items;
        $data['delivery_number']              =   $result->delivery->delivery_number;
        $data['delivery_date']              =   $result->delivery->delivery_date;
        $data['purpose']            =   $result->upr->purpose;
        $data['place']             =   $result->upr->place_of_delivery;
        $data['centers']            =   $result->upr->centers->name;
        $data['units']              =   $result->upr->unit->short_code;
        $data['ref_number']         =   $result->upr->ref_number;
        $data['supplier']           =   $supplier;
        $data['date']               =   $result->delivery->delivery_date;
        $data['po_number']          =   $result->delivery->po->po_number;
        $data['po_date']            =   $result->delivery->po->coa_approved_date;
        $data['invoice']            =   $result->delivery->inspections->invoices;
        $data['issues']             =   $result->delivery->diir->issues;
        $data['header']             =   $result->upr->centers;

        $data['receiver']           =   explode('/',$result->received_signatory);
        $data['inspector']          =   explode('/',$result->inspected_signatory);
        $data['approver']           =   explode('/',$result->approved_signatory);
        $data['issuer']             =   explode('/',$result->issued_signatory);
        $data['requestor']          =   explode('/',$result->requested_signatory);
        // dd($data);
        $pdf = PDF::loadView('forms.post-repair', ['data' => $data])
            ->setOption('margin-bottom', 30);
            // ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('diir.pdf');
    
    }



    /**
     * [viewPrintRIS description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrintRISNew(
        $id,
        DeliveryInspectionRepository $model,
        NOARepository $noa, HeaderRepository $headers
        )
    {
        $result                     =   $model->with(['receiver', 'approver','inspector','issuer','requestor','upr' ,'delivery'])->findById($id);

        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        }

        if($result->received_signatory == null)
        {
            return redirect()->back()->with([
                'error'  => "Please select signatory first"
            ]);
        }

        $header                     =  $headers->findByUnit($result->upr->units);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $data['items']              =   $result->delivery->po->items;
        $data['purpose']            =   $result->upr->purpose;
        $data['place']              =   $result->upr->place_of_delivery;
        $data['centers']            =   $result->upr->centers->name;
        $data['units']              =   $result->upr->unit->short_code;
        $data['ref_number']         =   $result->upr->ref_number;
        $data['supplier']           =   $supplier->name;
        $data['date']               =   $result->delivery->delivery_date;
        $data['po_number']          =   $result->delivery->po->po_number;
        $data['po_date']            =   $result->delivery->po->coa_approved_date;
        $data['invoice']            =   $result->delivery->inspections->invoices;
        $data['issues']             =   $result->delivery->diir->issues;
        $data['header']             =   $result->upr->centers;

        $data['receiver']           =   explode('/',$result->received_signatory);
        $data['inspector']          =   explode('/',$result->inspected_signatory);
        $data['approver']           =   explode('/',$result->approved_signatory);
        $data['issuer']             =   explode('/',$result->issued_signatory);
        $data['requestor']          =   explode('/',$result->requested_signatory);
        $data['bid_amount']         =   $result->delivery->po->bid_amount;
        // dd($data);
        $pdf = PDF::loadView('forms.ris-new', ['data' => $data])
            ->setOption('margin-bottom', 30);
            // ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('ris.pdf');
    }

    /**
     * [viewPrintRIS description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrintRIS(
        $id,
        DeliveryInspectionRepository $model,
        NOARepository $noa, HeaderRepository $headers
        )
    {
        $result                     =   $model->with(['receiver', 'approver','inspector','issuer','requestor','upr' ,'delivery'])->findById($id);

        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        }

        if($result->received_signatory == null)
        {
            return redirect()->back()->with([
                'error'  => "Please select signatory first"
            ]);
        }

        $header                     =  $headers->findByUnit($result->upr->units);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $data['items']              =   $result->delivery->po->items;
        $data['purpose']            =   $result->upr->purpose;
        $data['place']              =   $result->upr->place_of_delivery;
        $data['centers']            =   $result->upr->centers->name;
        $data['units']              =   $result->upr->unit->short_code;
        $data['ref_number']         =   $result->upr->ref_number;
        $data['supplier']           =   $supplier->name;
        $data['date']               =   $result->delivery->delivery_date;
        $data['po_number']          =   $result->delivery->po->po_number;
        $data['po_date']            =   $result->delivery->po->coa_approved_date;
        $data['invoice']            =   $result->delivery->inspections->invoices;
        $data['issues']             =   $result->delivery->diir->issues;
        $data['header']             =   $result->upr->centers;

        $data['receiver']           =   explode('/',$result->received_signatory);
        $data['inspector']          =   explode('/',$result->inspected_signatory);
        $data['approver']           =   explode('/',$result->approved_signatory);
        $data['issuer']             =   explode('/',$result->issued_signatory);
        $data['requestor']          =   explode('/',$result->requested_signatory);
        $data['bid_amount']         =   $result->delivery->po->bid_amount;
        // dd($data);
        $pdf = PDF::loadView('forms.diir', ['data' => $data])
            ->setOption('margin-bottom', 30);
            // ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('ris.pdf');
    }

    /**
     * [viewPrintRIS description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrintRISForm2(
        $id,
        RISRepository $ris,
        DeliveryInspectionRepository $model,
        NOARepository $noa, HeaderRepository $headers
        )
    {
        $result                     =   $model->with(['receiver', 'approver','inspector','issuer','requestor','upr' ,'delivery'])->findById($id);

        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        }

        if($result->received_signatory == null)
        {
            return redirect()->back()->with([
                'error'  => "Please select signatory first"
            ]);
        }

        $header                     =  $headers->findByUnit($result->upr->units);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $data['items']              =   $result->delivery->po->items;
        $data['purpose']            =   $result->upr->purpose;
        $data['place']              =   $result->upr->place_of_delivery;
        $data['centers']            =   $result->upr->centers->name;
        $data['units']              =   $result->upr->unit->short_code;
        $data['ref_number']         =   $result->upr->ref_number;
        $data['supplier']           =   $supplier->name;
        $data['date']               =   $result->delivery->delivery_date;
        $data['po_number']          =   $result->delivery->po->po_number;
        $data['po_date']            =   $result->delivery->po->coa_approved_date;
        $data['invoice']            =   $result->delivery->inspections->invoices;
        $data['issues']             =   $result->delivery->diir->issues;
        $data['header']             =   $result->upr->centers;

        $data['inspector']          =   explode('/',$result->inspected_signatory);
        $data['bid_amount']         =   $result->delivery->po->bid_amount;

        $issuer                     =   explode('/',$result->issued_signatory);
        $approver                   =   explode('/',$result->approved_signatory);
        $receiver                   =   explode('/',$result->received_signatory);
        $requestor                  =   explode('/',$result->requested_signatory);
        // dd($data);

        $form       =   $ris->findByUnit($result->upr->units);
        $contents   =   "";
        if($form != null) {

          $contents   =   $form->content;
        }
        else
        {
          $file_path = base_path()."/resources/views/forms/default-ris.blade.php";
          if(file_exists($file_path))
          {
            $contents = \File::get($file_path);
          }
        }
        $itemContent = "";
        foreach($data['items'] as $key=>$item)
        {
          $itemContent .= "<tr>";
          $itemContent .= "<td class='align-center'>";
          $itemContent .= $key+1;
          $itemContent .= "</td>";
          $itemContent .= "<td class='align-center'>";
          $itemContent .= $item->unit;
          $itemContent .= "</td>";
          $itemContent .= "<td class='align-left'>";
          $itemContent .= $item->description;
          $itemContent .= "</td>";
          $itemContent .= "<td class='align-center'>";
          $itemContent .= $item->quantity;
          $itemContent .= "</td>";
          $itemContent .= "<td class='align-right'>";
          $itemContent .= formatPrice($item->price_unit);
          $itemContent .= "</td>";
          $itemContent .= "<td class='align-right'>";
          $itemContent .= formatPrice($item->total_amount);
          $itemContent .= "</td>";
          $itemContent .= "</tr>";

        }
        $output = preg_replace_callback('~\{{(.*?)\}}~', function($key)use($data, $itemContent, $receiver, $requestor, $issuer, $approver, $result) {
            $variable['unitHeader']            = $data['unitHeader'];
            $variable['itemContent']                 = $itemContent;
            $variable['purpose']               = $result->upr->purpose;
            $variable['bid_amount']            = formatPrice($data['bid_amount']);
            $variable['bid_amount_word']       = translateToWords(str_replace(',', '', $data['bid_amount']));
            $variable['receiver_name']         = (count($receiver) > 1) ? $receiver[0] : "";
            $variable['receiver_ranks']        = (count($receiver) > 1) ? $receiver[1] : "";
            $variable['receiver_branch']       = (count($receiver) > 1) ? $receiver[2] : "";
            $variable['receiver_designation']  = (count($receiver) > 1) ? $receiver[3] : "";
            $variable['requestor_name']         = (count($requestor) > 1) ? $requestor[0] : "";
            $variable['requestor_ranks']        = (count($requestor) > 1) ? $requestor[1] : "";
            $variable['requestor_branch']       = (count($requestor) > 1) ? $requestor[2] : "";
            $variable['requestor_designation']  = (count($requestor) > 1) ? $requestor[3] : "";
            $variable['approver_name']         = (count($approver) > 1) ? $approver[0] : "";
            $variable['approver_ranks']        = (count($approver) > 1) ? $approver[1] : "";
            $variable['approver_branch']       = (count($approver) > 1) ? $approver[2] : "";
            $variable['approver_designation']  = (count($approver) > 1) ? $approver[3] : "";
            $variable['issuer_name']           = (count($issuer) > 1) ? $issuer[0] : "";
            $variable['issuer_ranks']          = (count($issuer) > 1) ? $issuer[1] : "";
            $variable['issuer_branch']         = (count($issuer) > 1) ? $issuer[2] : "";
            $variable['issuer_designation']    = (count($issuer) > 1) ? $issuer[3] : "";
            if(isset($variable[$key[1]]) ){
              return $variable[$key[1]];
            }
            return $key[1];
        },
        $contents);

        $pdf = PDF::loadView('forms.ris-form2', ['content' => $output])
            ->setOption('margin-bottom', 30);
            // ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('rsi2.pdf');
    }

    /**
     * [viewPrintRIS description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrintRIS2Form2(
        $id,
        RIS2Repository $ris2Form,
        DeliveryInspectionRepository $model,
        NOARepository $noa, HeaderRepository $headers
        )
    {
        $result                     =   $model->with(['receiver', 'approver','inspector','issuer','requestor','upr' ,'delivery'])->findById($id);

        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        }

        if($result->received_signatory == null)
        {
            return redirect()->back()->with([
                'error'  => "Please select signatory first"
            ]);
        }

        $header                     =  $headers->findByUnit($result->upr->units);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $data['items']              =   $result->delivery->po->items;
        $data['purpose']            =   $result->upr->purpose;
        $data['place']              =   $result->upr->place_of_delivery;
        $data['centers']            =   $result->upr->centers->name;
        $data['units']              =   $result->upr->unit->short_code;
        $data['ref_number']         =   $result->upr->ref_number;
        $data['supplier']           =   $supplier->name;
        $data['date']               =   $result->delivery->delivery_date;
        $data['po_number']          =   $result->delivery->po->po_number;
        $data['po_date']            =   $result->delivery->po->coa_approved_date;
        $data['invoice']            =   $result->delivery->inspections->invoices;
        $data['issues']             =   $result->delivery->diir->issues;
        $data['header']             =   $result->upr->centers;

        $data['inspector']          =   explode('/',$result->inspected_signatory);
        $data['bid_amount']         =   $result->delivery->po->bid_amount;

        $issuer                     =   explode('/',$result->issued_signatory);
        $approver                   =   explode('/',$result->approved_signatory);
        $receiver                   =   explode('/',$result->received_signatory);
        $requestor                  =   explode('/',$result->requested_signatory);
        // dd($data);

        $form       =   $ris2Form->findByUnit($result->upr->units);
        $contents   =   "";
        if($form != null) {

          $contents   =   $form->content;
        }
        else
        {
          $file_path = base_path()."/resources/views/forms/default-ris2.blade.php";
          if(file_exists($file_path))
          {
            $contents = \File::get($file_path);
          }
        }
        $itemContent = "";
        foreach($data['items'] as $key=>$item)
        {
          $itemContent .= "<tr>";
          $itemContent .= "<td class='align-center'>";
          $itemContent .= $key+1;
          $itemContent .= "</td>";
          $itemContent .= "<td class='align-center'>";
          $itemContent .= $item->unit;
          $itemContent .= "</td>";
          $itemContent .= "<td class='align-left'>";
          $itemContent .= $item->description;
          $itemContent .= "</td>";
          $itemContent .= "<td class='align-center'>";
          $itemContent .= $item->quantity;
          $itemContent .= "</td>";
          $itemContent .= "<td class='align-right'>";
          $itemContent .= formatPrice($item->price_unit);
          $itemContent .= "</td>";
          $itemContent .= "<td class='align-right'>";
          $itemContent .= formatPrice($item->total_amount);
          $itemContent .= "</td>";
          $itemContent .= "</tr>";

        }
        $output = preg_replace_callback('~\{{(.*?)\}}~', function($key)use($data, $itemContent, $receiver, $requestor, $issuer, $approver, $result) {
            $variable['unitHeader']            = $data['unitHeader'];
            $variable['items']                 = $itemContent;
            $variable['purpose']               = $result->upr->purpose;
            $variable['bid_amount']            = formatPrice($data['bid_amount']);
            $variable['bid_amount_word']       = translateToWords(str_replace(',', '', $data['bid_amount']));
            $variable['receiver_name']         = (count($receiver) > 1) ? $receiver[0] : "";
            $variable['receiver_ranks']        = (count($receiver) > 1) ? $receiver[1] : "";
            $variable['receiver_branch']       = (count($receiver) > 1) ? $receiver[2] : "";
            $variable['receiver_designation']  = (count($receiver) > 1) ? $receiver[3] : "";
            $variable['requestor_name']         = (count($requestor) > 1) ? $requestor[0] : "";
            $variable['requestor_ranks']        = (count($requestor) > 1) ? $requestor[1] : "";
            $variable['requestor_branch']       = (count($requestor) > 1) ? $requestor[2] : "";
            $variable['requestor_designation']  = (count($requestor) > 1) ? $requestor[3] : "";
            $variable['approver_name']         = (count($approver) > 1) ? $approver[0] : "";
            $variable['approver_ranks']        = (count($approver) > 1) ? $approver[1] : "";
            $variable['approver_branch']       = (count($approver) > 1) ? $approver[2] : "";
            $variable['approver_designation']  = (count($approver) > 1) ? $approver[3] : "";
            $variable['issuer_name']           = (count($issuer) > 1) ? $issuer[0] : "";
            $variable['issuer_ranks']          = (count($issuer) > 1) ? $issuer[1] : "";
            $variable['issuer_branch']         = (count($issuer) > 1) ? $issuer[2] : "";
            $variable['issuer_designation']    = (count($issuer) > 1) ? $issuer[3] : "";
            if(isset($variable[$key[1]]) ){
              return $variable[$key[1]];
            }
            return $key[1];
        },
        $contents);

        $pdf = PDF::loadView('forms.ris2-form2', ['content' => $output])
            ->setOption('margin-bottom', 30);
            // ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('rsi2.pdf');
    }

    /**
     * [viewPrintRSMI2 description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrintRSMI2(
        $id,
        RSMIRepository $rsmiForm,
        DeliveryInspectionRepository $model,
        NOARepository $noa, HeaderRepository $headers
        )
    {
        $result                     =   $model->with(['receiver', 'approver','inspector','issuer','requestor','upr' ,'delivery'])->findById($id);

        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        }

        if($result->received_signatory == null)
        {
            return redirect()->back()->with([
                'error'  => "Please select signatory first"
            ]);
        }

        $header                     =  $headers->findByUnit($result->upr->units);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $data['items']              =   $result->delivery->po->items;
        $data['purpose']            =   $result->upr->purpose;
        $data['place']              =   $result->upr->place_of_delivery;
        $data['centers']            =   $result->upr->centers->name;
        $data['units']              =   $result->upr->unit->short_code;
        $data['ref_number']         =   $result->upr->ref_number;
        $data['supplier']           =   $supplier->name;
        $data['date']               =   $result->delivery->delivery_date;
        $data['po_number']          =   $result->delivery->po->po_number;
        $data['po_date']            =   $result->delivery->po->coa_approved_date;
        $data['invoice']            =   $result->delivery->inspections->invoices;
        $data['issues']             =   $result->delivery->diir->issues;
        $data['header']             =   $result->upr->centers;

        $data['inspector']          =   explode('/',$result->inspected_signatory);
        $data['bid_amount']         =   $result->delivery->po->bid_amount;

        $issuer                     =   explode('/',$result->issued_signatory);
        $approver                   =   explode('/',$result->approved_signatory);
        $receiver                   =   explode('/',$result->received_signatory);
        $requestor                  =   explode('/',$result->requested_signatory);
        // dd($data);

        $form       =   $rsmiForm->findByUnit($result->upr->units);
        $contents   =   "";
        if($form != null) {

          $contents   =   $form->content;
        }
        else
        {
          $file_path = base_path()."/resources/views/forms/default-rsmi.blade.php";
          if(file_exists($file_path))
          {
            $contents = \File::get($file_path);
          }
        }
        $itemContent = "";
        foreach($data['items'] as $key=>$item)
        {
          $itemContent .= "<tr>";

          $itemContent .= "<td>";
          $itemContent .= "</td>";

          $itemContent .= "<td>";
          $itemContent .= "</td>";

          $itemContent .= "<td class='align-center'>";
          $itemContent .= $key+1;
          $itemContent .= "</td>";

          $itemContent .= "<td class='align-center'>";
          $itemContent .= $item->description;
          $itemContent .= "</td>";

          $itemContent .= "<td class='align-left'>";
          $itemContent .= $item->unit;
          $itemContent .= "</td>";

          $itemContent .= "<td class='align-center'>";
          $itemContent .= $item->quantity;
          $itemContent .= "</td>";

          $itemContent .= "<td class='align-right'>";
          $itemContent .= formatPrice($item->price_unit);
          $itemContent .= "</td>";

          $itemContent .= "<td class='align-right'>";
          $itemContent .= formatPrice($item->total_amount);
          $itemContent .= "</td>";

          $itemContent .= "</tr>";

        }
        $output = preg_replace_callback('~\{{(.*?)\}}~', function($key)use($data, $itemContent, $receiver, $requestor, $issuer, $approver, $result) {
            $variable['unitHeader']            = $data['unitHeader'];
            $variable['po_number']            = $data['po_number'];
            $variable['supplier']            = $data['supplier'];
            $variable['items']                 = $itemContent;
            $variable['purpose']               = $result->upr->purpose;
            $variable['bid_amount']            = formatPrice($data['bid_amount']);
            $variable['bid_amount_word']       = translateToWords(str_replace(',', '', $data['bid_amount']));
            $variable['receiver_name']         = (count($receiver) > 1) ? $receiver[0] : "";
            $variable['receiver_ranks']        = (count($receiver) > 1) ? $receiver[1] : "";
            $variable['receiver_branch']       = (count($receiver) > 1) ? $receiver[2] : "";
            $variable['receiver_designation']  = (count($receiver) > 1) ? $receiver[3] : "";
            $variable['requestor_name']         = (count($requestor) > 1) ? $requestor[0] : "";
            $variable['requestor_ranks']        = (count($requestor) > 1) ? $requestor[1] : "";
            $variable['requestor_branch']       = (count($requestor) > 1) ? $requestor[2] : "";
            $variable['requestor_designation']  = (count($requestor) > 1) ? $requestor[3] : "";
            $variable['approver_name']         = (count($approver) > 1) ? $approver[0] : "";
            $variable['approver_ranks']        = (count($approver) > 1) ? $approver[1] : "";
            $variable['approver_branch']       = (count($approver) > 1) ? $approver[2] : "";
            $variable['approver_designation']  = (count($approver) > 1) ? $approver[3] : "";
            $variable['issuer_name']           = (count($issuer) > 1) ? $issuer[0] : "";
            $variable['issuer_ranks']          = (count($issuer) > 1) ? $issuer[1] : "";
            $variable['issuer_branch']         = (count($issuer) > 1) ? $issuer[2] : "";
            $variable['issuer_designation']    = (count($issuer) > 1) ? $issuer[3] : "";
            if(isset($variable[$key[1]]) ){
              return $variable[$key[1]];
            }
            return $key[1];
        },
        $contents);

        $pdf = PDF::loadView('forms.rsmi-form2', ['content' => $output])
            ->setOption('margin-bottom', 30);
            // ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('rsi2.pdf');
    }

    /**
     * [viewPrintRAR2 description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrintRAR2(
        $id,
        RARRepository $rarForm,
        DeliveryInspectionRepository $model,
        NOARepository $noa, HeaderRepository $headers
        )
    {
        $result                     =   $model->with(['receiver', 'approver','inspector','issuer','requestor','upr' ,'delivery'])->findById($id);

        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        }

        if($result->received_signatory == null)
        {
            return redirect()->back()->with([
                'error'  => "Please select signatory first"
            ]);
        }

        $header                     =  $headers->findByUnit($result->upr->units);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $data['items']              =   $result->delivery->po->items;
        $data['purpose']            =   $result->upr->purpose;
        $data['place']              =   $result->upr->place_of_delivery;
        $data['centers']            =   $result->upr->centers->name;
        $data['units']              =   $result->upr->unit->short_code;
        $data['ref_number']         =   $result->upr->ref_number;
        $data['supplier']           =   $supplier->name;
        $data['date']               =   $result->delivery->delivery_date;
        $data['delivery_number']    =   $result->delivery->delivery_number;
        $data['po_number']          =   $result->delivery->po->po_number;
        $data['po_date']            =   $result->delivery->po->coa_approved_date;
        $data['invoice']            =   $result->delivery->inspections->invoices;
        $data['issues']             =   $result->delivery->diir->issues;
        $data['header']             =   $result->upr->centers;

        $data['inspector']          =   explode('/',$result->inspected_signatory);
        $data['bid_amount']         =   $result->delivery->po->bid_amount;

        $issuer                     =   explode('/',$result->issued_signatory);
        $approver                   =   explode('/',$result->approved_signatory);
        $receiver                   =   explode('/',$result->received_signatory);
        $requestor                  =   explode('/',$result->requested_signatory);
        // dd($data);

        $form       =   $rarForm->findByUnit($result->upr->units);
        $contents   =   "";
        if($form != null) {

          $contents   =   $form->content;
        }
        else
        {
          $file_path = base_path()."/resources/views/forms/default-rar.blade.php";
          if(file_exists($file_path))
          {
            $contents = \File::get($file_path);
          }
        }
        $itemContent = "";
        foreach($data['items'] as $key=>$item)
        {
          $itemContent .= "<tr>";
          $itemContent .= "<td class='align-center'>";
          $itemContent .= $key+1;
          $itemContent .= "</td>";
          $itemContent .= "<td class='align-center'>";
          $itemContent .= $item->unit;
          $itemContent .= "</td>";
          $itemContent .= "<td class='align-left'>";
          $itemContent .= $item->description;
          $itemContent .= "</td>";
          $itemContent .= "<td class='align-center'>";
          $itemContent .= $item->quantity;
          $itemContent .= "</td>";
          $itemContent .= "<td class='align-right'>";
          $itemContent .= formatPrice($item->price_unit);
          $itemContent .= "</td>";
          $itemContent .= "<td class='align-right'>";
          $itemContent .= formatPrice($item->total_amount);
          $itemContent .= "</td>";
          $itemContent .= "</tr>";

        }


        $invoices = "";
        foreach($result->delivery->inspections->invoices as $inv)
        {
            $invoice = $inv->invoice_number ." ". \Carbon\Carbon::createFromFormat('!Y-m-d',$inv->invoice_date)->format('d F Y')."<br>";
            $invoices .=  $invoice;
        }

        $output = preg_replace_callback('~\{{(.*?)\}}~', function($key)use($data,$invoices, $itemContent, $receiver, $requestor, $issuer, $approver, $result) {
            $variable['unitHeader']            = $data['unitHeader'];
            $variable['supplier']              = $data['supplier'];
            $variable['place']                 = $data['place'];
            $variable['delivery_number']       = $data['delivery_number'];
            $variable['items']                 = $itemContent;
            $variable['invoices']              = $invoices;
            $variable['purpose']               = $result->upr->purpose;
            $variable['delivery_date']         = \Carbon\Carbon::createFromFormat('!Y-m-d',$data['date'])->format('d F Y');
            $variable['bid_amount']            = formatPrice($data['bid_amount']);
            $variable['bid_amount_word']       = translateToWords(str_replace(',', '', $data['bid_amount']));
            $variable['receiver_name']         = (count($receiver) > 1) ? $receiver[0] : "";
            $variable['receiver_ranks']        = (count($receiver) > 1) ? $receiver[1] : "";
            $variable['receiver_branch']       = (count($receiver) > 1) ? $receiver[2] : "";
            $variable['receiver_designation']  = (count($receiver) > 1) ? $receiver[3] : "";
            $variable['requestor_name']         = (count($requestor) > 1) ? $requestor[0] : "";
            $variable['requestor_ranks']        = (count($requestor) > 1) ? $requestor[1] : "";
            $variable['requestor_branch']       = (count($requestor) > 1) ? $requestor[2] : "";
            $variable['requestor_designation']  = (count($requestor) > 1) ? $requestor[3] : "";
            $variable['approver_name']         = (count($approver) > 1) ? $approver[0] : "";
            $variable['approver_ranks']        = (count($approver) > 1) ? $approver[1] : "";
            $variable['approver_branch']       = (count($approver) > 1) ? $approver[2] : "";
            $variable['approver_designation']  = (count($approver) > 1) ? $approver[3] : "";
            $variable['issuer_name']           = (count($issuer) > 1) ? $issuer[0] : "";
            $variable['issuer_ranks']          = (count($issuer) > 1) ? $issuer[1] : "";
            $variable['issuer_branch']         = (count($issuer) > 1) ? $issuer[2] : "";
            $variable['issuer_designation']    = (count($issuer) > 1) ? $issuer[3] : "";
            if(isset($variable[$key[1]]) ){
              return $variable[$key[1]];
            }
            return $key[1];
        },
        $contents);

        $pdf = PDF::loadView('forms.rar-form2', ['content' => $output])
            ->setOption('margin-bottom', 30);
            // ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('rsi2.pdf');
    }

    /**
     * [viewPrintCOI2 description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrintCOI2(
        $id,
        COIRepository $coiForm,
        DeliveryInspectionRepository $model,
        NOARepository $noa, HeaderRepository $headers
        )
    {
        $result                     =   $model->with(['receiver', 'approver','inspector','issuer','requestor','upr' ,'delivery'])->findById($id);

        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        }

        if($result->received_signatory == null)
        {
            return redirect()->back()->with([
                'error'  => "Please select signatory first"
            ]);
        }

        $header                     =  $headers->findByUnit($result->upr->units);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $data['items']              =   $result->delivery->po->items;
        $data['purpose']            =   $result->upr->purpose;
        $data['delivery_number']    =   $result->delivery->delivery_number;
        $data['place']              =   $result->upr->place_of_delivery;
        $data['centers']            =   $result->upr->centers->name;
        $data['delivery_date']      =   $result->delivery->delivery_date;
        $data['units']              =   $result->upr->unit->short_code;
        $data['ref_number']         =   $result->upr->ref_number;
        $data['supplier']           =   $supplier;
        $data['date']               =   $result->delivery->delivery_date;
        $data['po_number']          =   $result->delivery->po->po_number;
        $data['po_date']            =   $result->delivery->po->coa_approved_date;
        $data['invoice']            =   $result->delivery->inspections->invoices;
        $data['issues']             =   $result->delivery->diir->issues;
        $data['header']             =   $result->upr->centers;
        $data['nature_of_delivery'] =   $result->upr->inspections->nature_of_delivery;
        $data['recommendation']     =   $result->upr->inspections->recommendation;
        $data['inspection_date']    =   $result->upr->inspections->inspection_date;
        $data['findings']           =   $result->upr->inspections->findings;

        $data['inspector']          =   explode('/',$result->inspected_signatory);
        $data['bid_amount']         =   $result->delivery->po->bid_amount;

        $issuer                     =   explode('/',$result->issued_signatory);
        $approver                   =   explode('/',$result->approved_signatory);
        $receiver                   =   explode('/',$result->received_signatory);
        $requestor                  =   explode('/',$result->requested_signatory);
        // dd($data);

        $form       =   $coiForm->findByUnit($result->upr->units);
        $contents   =   "";
        if($form != null) {

          $contents   =   $form->content;
        }
        else
        {
          $file_path = base_path()."/resources/views/forms/default-coi.blade.php";
          if(file_exists($file_path))
          {
            $contents = \File::get($file_path);
          }
        }
        $itemContent = "";
        foreach($data['items'] as $key=>$item)
        {
          $itemContent .= "<tr>";
          $itemContent .= "<td class='align-center'>";
          $itemContent .= $key+1;
          $itemContent .= "</td>";
          $itemContent .= "<td class='align-center'>";
          $itemContent .= $item->unit;
          $itemContent .= "</td>";
          $itemContent .= "<td class='align-left'>";
          $itemContent .= $item->description;
          $itemContent .= "</td>";
          $itemContent .= "<td class='align-center'>";
          $itemContent .= $item->quantity;
          $itemContent .= "</td>";
          $itemContent .= "<td class='align-right'>";
          $itemContent .= formatPrice($item->price_unit);
          $itemContent .= "</td>";
          $itemContent .= "<td class='align-right'>";
          $itemContent .= formatPrice($item->total_amount);
          $itemContent .= "</td>";
          $itemContent .= "</tr>";

        }


        $invoices = "";
        foreach($result->delivery->inspections->invoices as $inv)
        {
            $invoice = $inv->invoice_number ." ". \Carbon\Carbon::createFromFormat('!Y-m-d',$inv->invoice_date)->format('d F Y')."<br>";
            $invoices .=  $invoice;
        }

        $output = preg_replace_callback('~\{{(.*?)\}}~', function($key)use($data, $invoices, $itemContent, $receiver, $requestor, $issuer, $approver, $result) {
            $variable['unitHeader']                      = $data['unitHeader'];
            $variable['nature_of_delivery']  = $data['nature_of_delivery'];
            $variable['recommendation']      = $data['recommendation'];
            $variable['po_number']            = $data['po_number'];
            $variable['findings']            = $data['findings'];
            $variable['invoices']              = $invoices;
            $variable['items']                 = $itemContent;
            $variable['delivery_date']         = \Carbon\Carbon::createFromFormat('!Y-m-d',$data['date'])->format('d F Y');
            $variable['inspection_date']         = \Carbon\Carbon::createFromFormat('!Y-m-d',$data['inspection_date'])->format('d F Y');
            $variable['supplier_name']         = $data['supplier']->name;
            $variable['supplier_address']      = $data['supplier']->address;
            $variable['place']                 = $data['place'];
            $variable['delivery_number']       = $data['delivery_number'];
            $variable['purpose']               = $result->upr->purpose;
            $variable['bid_amount']            = formatPrice($data['bid_amount']);
            $variable['bid_amount_word']       = translateToWords(str_replace(',', '', $data['bid_amount']));
            $variable['receiver_name']         = (count($receiver) > 1) ? $receiver[0] : "";
            $variable['receiver_ranks']        = (count($receiver) > 1) ? $receiver[1] : "";
            $variable['receiver_branch']       = (count($receiver) > 1) ? $receiver[2] : "";
            $variable['receiver_designation']  = (count($receiver) > 1) ? $receiver[3] : "";
            $variable['requestor_name']         = (count($requestor) > 1) ? $requestor[0] : "";
            $variable['requestor_ranks']        = (count($requestor) > 1) ? $requestor[1] : "";
            $variable['requestor_branch']       = (count($requestor) > 1) ? $requestor[2] : "";
            $variable['requestor_designation']  = (count($requestor) > 1) ? $requestor[3] : "";
            $variable['approver_name']         = (count($approver) > 1) ? $approver[0] : "";
            $variable['approver_ranks']        = (count($approver) > 1) ? $approver[1] : "";
            $variable['approver_branch']       = (count($approver) > 1) ? $approver[2] : "";
            $variable['approver_designation']  = (count($approver) > 1) ? $approver[3] : "";
            $variable['issuer_name']           = (count($issuer) > 1) ? $issuer[0] : "";
            $variable['issuer_ranks']          = (count($issuer) > 1) ? $issuer[1] : "";
            $variable['issuer_branch']         = (count($issuer) > 1) ? $issuer[2] : "";
            $variable['issuer_designation']    = (count($issuer) > 1) ? $issuer[3] : "";
            if(isset($variable[$key[1]]) ){
              return $variable[$key[1]];
            }
            return $key[1];
        },
        $contents);

        $pdf = PDF::loadView('forms.coi-form2', ['content' => $output])
            ->setOption('margin-bottom', 30);
            // ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('rsi2.pdf');
    }

    /**
     * [viewPrintRIS2 description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrintRIS2(
        $id,
        DeliveryInspectionRepository $model,
        NOARepository $noa, HeaderRepository $headers
        )
    {
        $result                     =   $model->with(['receiver', 'approver','inspector','issuer','requestor','upr' ,'delivery'])->findById($id);

        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        }

        if($result->received_signatory == null || $result->inspected_signatory == null|| $result->approved_signatory == null|| $result->issued_signatory == null|| $result->requested_signatory == null)
        {
            return redirect()->back()->with([
                'error'  => "Please select signatory first"
            ]);
        }

        $header                     =  $headers->findByUnit($result->upr->units);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $data['items']              =   $result->delivery->po->items;
        $data['purpose']            =   $result->upr->purpose;
        $data['place']              =   $result->upr->place_of_delivery;
        $data['centers']            =   $result->upr->centers->name;
        $data['units']              =   $result->upr->unit->short_code;
        $data['ref_number']         =   $result->upr->ref_number;
        $data['supplier']           =   $supplier->name;
        $data['date']               =   $result->delivery->delivery_date;
        $data['po_number']          =   $result->delivery->po->po_number;
        $data['po_date']            =   $result->delivery->po->coa_approved_date;
        $data['invoice']            =   $result->delivery->inspections->invoices;
        $data['issues']             =   $result->delivery->diir->issues;
        $data['header']             =   $result->upr->centers;
        $data['bid_amount']         =   $result->delivery->po->bid_amount;

        $data['receiver']           =   explode('/',$result->received_signatory);
        $data['inspector']          =   explode('/',$result->inspected_signatory);
        $data['approver']           =   explode('/',$result->approved_signatory);
        $data['issuer']             =   explode('/',$result->issued_signatory);
        $data['requestor']          =   explode('/',$result->requested_signatory);
        // dd($data);
        $pdf = PDF::loadView('forms.diir2', ['data' => $data])
            ->setOption('margin-bottom', 30);
            // ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('rsi2.pdf');
    }

    /**
     * [viewPrintRAR description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrintRAR(
        $id,
        DeliveryInspectionRepository $model,
        NOARepository $noa, HeaderRepository $headers
        )
    {
        $result                     =   $model->with(['receiver', 'approver','inspector','issuer','requestor','upr' ,'delivery'])->findById($id);

        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        }

        if($result->received_signatory == null || $result->inspected_signatory == null|| $result->approved_signatory == null|| $result->issued_signatory == null|| $result->requested_signatory == null)
        {
            return redirect()->back()->with([
                'error'  => "Please select signatory first"
            ]);
        }

        $header                     =  $headers->findByUnit($result->upr->units);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $data['items']              =   $result->delivery->po->items;
        $data['delivery_number']    =   $result->delivery->delivery_number;
        $data['delivery_date']      =   $result->delivery->delivery_date;
        $data['purpose']            =   $result->upr->purpose;
        $data['place']              =   $result->upr->place_of_delivery;
        $data['centers']            =   $result->upr->centers->name;
        $data['units']              =   $result->upr->unit->short_code;
        $data['ref_number']         =   $result->upr->ref_number;
        $data['supplier']           =   $supplier->name;
        $data['date']               =   $result->delivery->delivery_date;
        $data['po_number']          =   $result->delivery->po->po_number;
        $data['po_date']            =   $result->delivery->po->coa_approved_date;
        $data['invoice']            =   $result->delivery->inspections->invoices;
        $data['issues']             =   $result->delivery->diir->issues;
        $data['header']             =   $result->upr->centers;

        $data['receiver']           =   explode('/',$result->received_signatory);
        $data['inspector']          =   explode('/',$result->inspected_signatory);
        $data['approver']           =   explode('/',$result->approved_signatory);
        $data['issuer']             =   explode('/',$result->issued_signatory);
        $data['requestor']          =   explode('/',$result->requested_signatory);
        $data['bid_amount']         =   $result->delivery->po->bid_amount;
        // dd($data);
        $pdf = PDF::loadView('forms.rar', ['data' => $data])
            ->setOption('margin-bottom', 30);
            // ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('rar.pdf');
    }

    /**
     * [viewPrintRAR description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrintCOI(
        $id,
        DeliveryInspectionRepository $model,
        NOARepository $noa, HeaderRepository $headers
        )
    {
        $result                     =   $model->with(['receiver', 'approver','inspector','issuer','requestor','upr' ,'delivery'])->findById($id);

        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        }


        if($result->received_signatory == null || $result->inspected_signatory == null|| $result->approved_signatory == null|| $result->issued_signatory == null|| $result->requested_signatory == null)
        {
            return redirect()->back()->with([
                'error'  => "Please select signatory first"
            ]);
        }
        $header                     =  $headers->findByUnit($result->upr->units);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $data['invoice']            =   $result->upr->inspections->invoices;
        $data['nature_of_delivery'] =   $result->upr->inspections->nature_of_delivery;
        $data['recommendation']     =   $result->upr->inspections->recommendation;
        $data['inspection_date']    =   $result->upr->inspections->inspection_date;
        $data['findings']           =   $result->upr->inspections->findings;
        $data['items']              =   $result->delivery->po->items;
        $data['delivery_number']    =   $result->delivery->delivery_number;
        $data['delivery_date']      =   $result->delivery->delivery_date;
        $data['purpose']            =   $result->upr->purpose;
        $data['place']              =   $result->upr->place_of_delivery;
        $data['centers']            =   $result->upr->centers->name;
        $data['units']              =   $result->upr->unit->short_code;
        $data['ref_number']         =   $result->upr->ref_number;
        $data['supplier']           =   $supplier;
        $data['date']               =   $result->delivery->delivery_date;
        $data['po_number']          =   $result->delivery->po->po_number;
        $data['po_date']            =   $result->delivery->po->coa_approved_date;
        $data['invoice']            =   $result->delivery->inspections->invoices;
        $data['issues']             =   $result->delivery->diir->issues;
        $data['header']             =   $result->upr->centers;
        $data['chairman']           =   explode('/',$result->chairman_signatory_name);
        $data['one']          =   explode('/',$result->signatory_one_name);
        $data['two']           =   explode('/',$result->signatory_two_name);
        $data['three']             =   explode('/',$result->signatory_three_name);
        $data['four']          =   explode('/',$result->signatory_four_name);
        $data['five']          =   explode('/',$result->signatory_five_name);
        // dd($data);
        $pdf = PDF::loadView('forms.coi', ['data' => $data])
            ->setOption('margin-bottom', 30);
            // ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('coi.pdf');
    }

    /**
     * [viewPrintRSMI description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrintRSMI(
        $id,
        DeliveryInspectionRepository $model,
        NOARepository $noa, HeaderRepository $headers
        )
    {
        $result                     =   $model->with(['receiver', 'approver','inspector','issuer','requestor','upr' ,'delivery'])->findById($id);

        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
            $win                =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner;
        }
        else
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
            $win                =   $noa->with('winner')->findByUPR($result->upr_id)->winner;
        }
        if($result->received_signatory == null || $result->inspected_signatory == null|| $result->approved_signatory == null|| $result->issued_signatory == null|| $result->requested_signatory == null)
        {
            return redirect()->back()->with([
                'error'  => "Please select signatory first"
            ]);
        }

        $header                     =  $headers->findByUnit($result->upr->units);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $data['items']              =   $result->delivery->po->items;
        $data['purpose']            =   $result->upr->purpose;
        $data['place']              =   $result->upr->place_of_delivery;
        $data['centers']            =   $result->upr->centers->name;
        $data['units']              =   $result->upr->unit->short_code;
        $data['ref_number']         =   $result->upr->ref_number;
        $data['supplier']           =   $supplier->name;
        $data['date']               =   $result->delivery->delivery_date;
        $data['po_number']          =   $result->delivery->po->po_number;
        $data['po_date']            =   $result->delivery->po->coa_approved_date;
        $data['invoice']            =   $result->delivery->inspections->invoices;
        $data['issues']             =   $result->delivery->diir->issues;

        $data['sao']                =   explode('/',$result->upr->inspections->sao_name_signatory);
        $data['inspector']          =   explode('/',$result->inspected_signatory);
        $data['approver']           =   explode('/',$result->approved_signatory);
        $data['approver']           =   explode('/',$result->approved_signatory);
        $data['issuer']             =   explode('/',$result->issued_signatory);
        $data['requestor']          =   explode('/',$result->requested_signatory);
        $data['header']             =   $result->upr->centers;
        $data['po']          =   $result->delivery->po;
        $data['bid_amount']         =   $win->bid_amount;
        // dd($data);
        $pdf = PDF::loadView('forms.rsmi', ['data' => $data])
            ->setOption('margin-bottom', 30);
            // ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('rsmi.pdf');
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
            'model'         =>  $data_model,
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('DIIR', 'procurements.delivered-inspections.show',$data_model->id),
                new Breadcrumb('Logs'),
            ]
        ]);
    }
}
