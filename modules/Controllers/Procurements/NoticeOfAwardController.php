<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use Carbon\Carbon;
use Validator;
use \App\Support\Breadcrumb;
use App\Events\Event;

use \Revlv\Procurements\NoticeOfAward\NOARepository;
use \Revlv\Procurements\Canvassing\CanvassingRepository;
use \Revlv\Procurements\Canvassing\CanvassingRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\RFQProponents\RFQProponentRepository;
use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\Holidays\HolidayRepository;
use \Revlv\Users\Logs\UserLogRepository;
use \Revlv\Users\UserRepository;
use \Revlv\Settings\Forms\Header\HeaderRepository;
use \Revlv\Settings\Forms\PCCOHeader\PCCOHeaderRepository;

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
    protected $noa;
    protected $signatories;
    protected $proponents;
    protected $audits;
    protected $holidays;
    protected $users;
    protected $userLogs;
    protected $headers;
    protected $pccoHeaders;

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
        SignatoryRepository $signatories,
        UnitPurchaseRequestRepository $upr,
        NOARepository $noa,
        $canvasId,
        $proponentId,
        HolidayRepository $holidays
        )
    {
        $canvasModel            =   $model->findById($canvasId);
        $canvasDate             =   $canvasModel->canvass_date;
        $canvasDate             =   Carbon::createFromFormat('Y-m-d', $canvasDate );
        // $canvasDate             =   $canvasModel->rfq->completed_at;
        //
        $validator = Validator::make($request->all(),[
            'awarded_date'  =>   'required|after_or_equal:'.$canvasDate->format('Y-m-d'),
            'awarded_by'    =>   'required',
            'seconded_by'   =>   'required',
            'account_type'  =>   'required',
        ]);

        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('awarded_date') );

        $proponent_model        =   $proponents->with('supplier')->findById($proponentId);

        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $supplier_name          =   $proponent_model->supplier->name;
        $cd                     =   $canvasDate->diffInDays($transaction_date);

        $day_delayed            =   $canvasDate->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;
        if($day_delayed > 2)
        {
            $day_delayed = $day_delayed - 2;
        }


        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('remarks') == null && $day_delayed > 2 ) {
                $validator->errors()->add('remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('action') == null && $day_delayed > 2) {
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

        $signatory  =    $signatories->findById($canvasModel->chief);
        $signatory   =   $signatory->name."/".$signatory->ranks."/".$signatory->branch."/".$signatory->designation;

        $data   =   [
            'canvass_id'    =>  $canvasId,
            'upr_id'        =>  $canvasModel->upr_id,
            'rfq_id'        =>  $canvasModel->rfq_id,
            'rfq_number'    =>  $canvasModel->rfq_number,
            'signatory_id'  =>  $canvasModel->chief,
            'signatory'     =>  $signatory,
            'upr_number'    =>  $canvasModel->upr_number,
            'proponent_id'  =>  $proponentId,
            'awarded_by'    =>  $request->awarded_by,
            'seconded_by'   =>  $request->seconded_by,
            'awarded_date'  =>  $request->awarded_date,
            'remarks'       =>  $request->remarks,
            'action'        =>  $request->action,
            'days'          =>  $day_delayed,
        ];

        $noa->save($data);

        // // Update canvass adjuourned time
        $model->update(['adjourned_time' => \Carbon\Carbon::now(), 'resolution' => $request->resolution], $canvasId);

        // // update upr
        $upr_result =   $upr->update([
            'next_allowable'=> 1,
            'next_step'     => 'Issue NOA',
            'next_due'      => $transaction_date->addDays(1),
            'last_date'     => $transaction_date,
            'calendar_days' => $cd + $canvasModel->upr->calendar_days,
            'status'        => "Awarded To $supplier_name",
            'delay_count'   => $day_delayed,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ],  $canvasModel->upr_id);

        event(new Event($upr_result, $upr_result->upr_number." Award NOA"));


        return redirect()->route('procurements.canvassing.show', $canvasId)->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     * [getDatatable description]
     *
     * @return [type]            [description]
     */
    public function getDatatable(NOARepository $model)
    {
        return $model->getDatatable();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, NOARepository $model)
    {
        return $this->view('modules.procurements.noa.index',[
            'resources'     =>  $model->paginateByRequest(10, $request, 'alternative'),
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('Notice Of Award'),
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
    public function update(
        Request $request,
        $id,
        RFQProponentRepository $rfq,
        BlankRFQRepository $blank,
        UnitPurchaseRequestRepository $upr,
        NOARepository $model,
        HolidayRepository $holidays
        )
    {
        $noaModel       =   $model->findById($id);
        $holiday_lists  =   $holidays->lists('id','holiday_date');

        $accepted_date =   Carbon::createFromFormat('Y-m-d', $noaModel->accepted_date);

        $award_accepted_date  =   Carbon::createFromFormat('Y-m-d', $request->award_accepted_date);

        $cd                   =   $accepted_date->diffInDays($award_accepted_date);

        $day_delayed          =   $accepted_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $award_accepted_date);

        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;
        if($day_delayed > 1)
        {
            $day_delayed = $day_delayed - 1;
        }

        $validator = Validator::make($request->all(),[
            'received_by'           =>  'required',
            'award_accepted_date'   =>  'required|after_or_equal:'. $accepted_date->format('Y-m-d'),
            'received_action'       =>  'required_with:received_remarks'
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('received_remarks') == null && $day_delayed > 1) {
                $validator->errors()->add('received_remarks', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Please Check Your Fields.'])
                        ->withErrors($validator)
                        ->withInput();
        }

        $input  =   [
            'received_by'           =>  $request->received_by,
            'award_accepted_date'   =>  $request->award_accepted_date,
            'status'                =>  'accepted',
            'received_days'         =>  $day_delayed,
            'received_remarks'      =>  $request->received_remarks,
            'account_type'          =>  $request->account_type,
            'received_action'       =>  $request->received_action,
            'action'                =>  $request->action,
            'remarks'               =>  $request->remarks,
        ];
        $result             =   $model->findById($id);

        $model->update($input, $id);

        $nextStep           =   'Preparation of PO';
        // if($result->upr->total_amount > 50000){
        //   $nextStep           =   'Posting of NOA to Philgeps';
        // }

        $upr_result = $upr->update([
            'next_allowable'=> 2,
            'next_step'     => $nextStep,
            'next_due'      => $award_accepted_date->addDays(2),
            'last_date'     => $award_accepted_date,
            'status'        => 'Conforme NOA',
            'delay_count'   => $day_delayed,
            'calendar_days' => $cd + $result->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr_id);

        event(new Event($upr_result, $upr_result->upr_number." Conforme NOA"));


        return redirect()->back()->with([
            'success'  => "Record has been successfully updated."
        ]);
    }



    /**
     * [philgepsPosting description]
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function philgepsPosting(
        Request $request,
        $id,
        NOARepository $model,
        UnitPurchaseRequestRepository $upr,
        HolidayRepository $holidays)
    {
        $noaModel       =   $model->findById($id);
        $holiday_lists  =   $holidays->lists('id','holiday_date');

        $accepted_date =   Carbon::createFromFormat('Y-m-d', $noaModel->award_accepted_date);

        $award_accepted_date  =   Carbon::createFromFormat('Y-m-d', $request->philgeps_posting);

        $cd                   =   $accepted_date->diffInDays($award_accepted_date);

        $day_delayed          =   $accepted_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $award_accepted_date);

        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;
        if($day_delayed > 1)
        {
            $day_delayed = $day_delayed - 1;
        }

        $validator = Validator::make($request->all(),[
            'philgeps_posting'   =>  'required|after_or_equal:'. $accepted_date->format('Y-m-d'),
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('philgeps_remarks') == null && $day_delayed > 1) {
                $validator->errors()->add('philgeps_remarks', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Please Check Your Fields.'])
                        ->withErrors($validator)
                        ->withInput();
        }

        $input  =   [
            'philgeps_posting'      =>  $request->philgeps_posting,
            'philgeps_days'         =>  $wd,
            'philgeps_remarks'      =>  $request->philgeps_remarks,
            'philgeps_action'       =>  $request->philgeps_action,
        ];

        $result             =   $model->findById($id);

        $model->update($input, $id);
        // $upr_result = $upr->update([
        //     'next_allowable'=> 2,
        //     'next_step'     => 'Preparation of PO',
        //     'next_due'      => $award_accepted_date->addDays(2),
        //     'last_date'     => $award_accepted_date,
        //     'status'        => '  Philgeps Posting',
        //     'delay_count'   => $day_delayed,
        //     'calendar_days' => $cd + $result->upr->calendar_days,
        //     'last_action'   => $request->action,
        //     'last_remarks'  => $request->remarks
        //     ], $result->upr_id);

        event(new Event($upr_result, $upr_result->upr_number." Posting of NOA to Philgeps"));


        return redirect()->back()->with([
            'success'  => "Record has been successfully updated."
        ]);
    }


    /**
     * [acceptNOA description]
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function acceptNOA(
        Request $request,
        NOARepository $model,
        UnitPurchaseRequestRepository $upr,
        HolidayRepository $holidays)
    {
        $id             =   $request->id;
        $noaModel       =   $model->findById($id);
        $holiday_lists  =   $holidays->lists('id','holiday_date');
        $noa_award_date =   Carbon::createFromFormat('Y-m-d H:i:s', $noaModel->awarded_date);

        $validator = Validator::make($request->all(),[
            'file'          =>   'required',
            'accepted_date' =>   'required|after_or_equal:'. $noa_award_date->format('Y-m-d'),
        ]);
        $accepted_date  =   Carbon::createFromFormat('Y-m-d', $request->accepted_date);
        $cd                     =   $noa_award_date->diffInDays($accepted_date);

        $day_delayed            =   $noa_award_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $accepted_date);

        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed > 1)
        {
            $day_delayed = $day_delayed - 1;
        }


        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('approved_remarks') == null && $day_delayed > 1) {
                $validator->errors()->add('approved_remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('approved_action') == null && $day_delayed > 1) {
                $validator->errors()->add('approved_action', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Please Check Your Fields.'])
                        ->withErrors($validator)
                        ->withInput();
        }

        $data       =   [
            'accepted_date'     =>  $request->accepted_date,
            'status'            =>  'approved',
            'file'              =>  '',
            'approved_days'     =>  $wd,
            'approved_remarks'  =>  $request->approved_remarks,
            'approved_action'  =>  $request->approved_action,
        ];

        if($request->file)
        {
            $file       = md5_file($request->file);
            $file       = $file.".".$request->file->getClientOriginalExtension();
            $data['file']   =   $file;
        }


        $result =   $model->update($data, $id);

        if($result && $request->has('file'))
        {
            $path       = $request->file->storeAs('noa-attachments', $file);
        }

         // // update upr

        $upr_result =   $upr->update([
            'next_allowable'=> 1,
            'next_step'     => 'Recieved NOA',
            'next_due'      => $noa_award_date->addDays(1),
            'last_date'     => $accepted_date,
            'status'        => "Issue NOA",
            'delay_count'   => $day_delayed,
            'calendar_days' => $cd + $result->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ],  $result->upr_id);

        event(new Event($upr_result, $upr_result->upr_number." Issue NOA"));


        return redirect()->back()->with([
            'success'  => "NOA has been successfully accepted."
        ]);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(
        $id,
        SignatoryRepository $signatories,
        RFQProponentRepository $proponents,
        NOARepository $model)
    {
        $result             =   $model->findById($id);

        $plists             =   [];
        $proponent_list     =   $proponents->findByRFQId($result->rfq_id);

        foreach($proponent_list as $list){
            $plists[$list->id] = $list->supplier->name;
        }

        $signatory_list     =   $signatories->lists('id','name');
        return $this->view('modules.procurements.noa.edit',[
            'data'              =>  $result,
            'signatory_lists'   =>  $signatory_list,
            'proponent_list'    =>  $plists,
            'indexRoute'        =>  $this->baseUrl.'show',
            'modelConfig'       =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update-dates', $id],
                    'method'    =>  'PUT',
                    'novalidate'=>  'novalidate'
                ],
                'destroy'   => [
                    'route' => [$this->baseUrl.'destroy',$id],
                    'method'=> 'DELETE'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('Notice of Award', 'procurements.noa.show', $result->id),
                new Breadcrumb('Update'),
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
        NOARepository $noa,
        $id,
        RFQProponentRepository $proponents,
        SignatoryRepository $signatories,
        UnitPurchaseRequestRepository $upr)
    {
        $result             =   $noa->with(['winner', 'upr'])->findById($id);
        $canvass            =   $model->findById($result->canvass_id);

        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $proponent_awardee  =   $result->biddingWinner->supplier;

            if(!$result->biddingWinner)
            {
                return redirect()->route('procurements.blank-rfq.show', $id)->with([
                    'success'    =>  'Awardee is not yet present. Go to canvassing and select proponent.'
                ]);
            }

        }
        else
        {
            $proponent_awardee  =   $result->winner->supplier;

            if(!$result->winner)
            {
                return redirect()->route('procurements.blank-rfq.show', $id)->with([
                    'success'    =>  'Awardee is not yet present. Go to canvassing and select proponent.'
                ]);
            }

        }

        $upr_model          =   $upr->with(['centers','modes','unit','charges','terms','users'])->findByRFQId($proponent_awardee->rfq_id);

        $signatory_list     =   $signatories->lists('id','name');

        return $this->view('modules.procurements.noa.show',[
            'data'          =>  $result,
            'upr_model'     =>  $upr_model,
            'canvass'       =>  $canvass,
            'supplier'      =>  $proponent_awardee,
            'signatory_list'=>  $signatory_list,
            'printRoute'    =>  $this->baseUrl.'print',
            'printRoute2'    =>  $this->baseUrl.'print2',
            'indexRoute'    =>  $this->baseUrl.'index',
            'editRoute'     =>  $this->baseUrl.'edit',
            'modelConfig'   =>  [
                'receive_award' =>  [
                    'route'     =>  [$this->baseUrl.'update', $result->id]
                ],
                'ntp-philgeps' =>  [
                    'route'     =>  [$this->baseUrl.'philgeps', $id],
                    'method'    =>  'PUT'
                ],
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update-signatory', $id],
                    'method'    =>  'PUT'
                ],
            ],
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb($result->upr_number, 'procurements.unit-purchase-requests.show', $result->upr_id),
                new Breadcrumb('Notice Of Award'),
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
    public function updateSignatory(Request $request, $id, NOARepository $model, SignatoryRepository $signatories)
    {
        $this->validate($request, [
            'signatory_id'   =>  'required',
        ]);

        if($upr_model->signatory_id != $request->signatory_id)
        {
            $signatory  =   $signatories->findById($request->signatory_id);
            $signatory   =   $signatory->name."/".$signatory->ranks."/".$signatory->branch."/".$signatory->designation;
        }

        $model->update(['signatory_id' =>$request->signatory_id, 'signatory' => $signatory], $id);

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
    public function updateDates(
        Request $request,
        $id,
        UnitPurchaseRequestRepository $upr,
        AuditLogRepository $audits,
        HolidayRepository $holidays,
        SignatoryRepository $signatories,
        UserLogRepository $userLogs,
        UserRepository $users,
        NOARepository $model
        )
    {
        $result             =   $model->findById($id);

        if($result->rfq_id)
        {
            $canvasModel            =   $result->canvass;
            $canvasDate             =   Carbon::createFromFormat('!Y-m-d', $canvasModel->canvass_date);
            $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('awarded_date') );

            $holiday_lists          =   $holidays->lists('id','holiday_date');

            $day_delayed            =   $canvasDate->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
                return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
            }, $transaction_date);
        }
        else
        {
            $pq_model               =   $result->pq;
            $pqDate                 =   Carbon::createFromFormat('!Y-m-d', $pq_model->transaction_date);
            $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('awarded_date') );

            $holiday_lists          =   $holidays->lists('id','holiday_date');

            $day_delayed            =   $pqDate->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
                return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
            }, $transaction_date);
        }

        $modelType  =   'Revlv\Procurements\NoticeOfAward\NOAEloquent';
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

        $signatory = $result->signatory;

        if($result->signatory_id != $request->signatory_id)
        {
            $signatory  =   $signatories->findById($request->signatory_id);
            $signatory   =   $signatory->name."/".$signatory->ranks."/".$signatory->branch."/".$signatory->designation;
        }


        $input  =   [
            'received_by'               =>  $request->received_by,
            'signatory'                 =>  $signatory,
            'signatory_id'              =>  $request->signatory_id,
            'awarded_date'              =>  $request->awarded_date,
            'award_accepted_date'       =>  $request->award_accepted_date,
            'accepted_date'             =>  $request->accepted_date,
            'account_type'              =>  $request->account_type,
            'update_remarks'            =>  $request->update_remarks,
            'days' => $day_delayed,
            'action'                =>  $request->action,
            'remarks'               =>  $request->remarks,
            'approved_action'                =>  $request->approved_action,
            'approved_remarks'               =>  $request->approved_remarks,
            'received_action'                =>  $request->received_action,
            'received_remarks'               =>  $request->received_remarks,
        ];
        if($request->proponent_id != null){
            $input['proponent_id'] = $request->proponent_id;
        }



        $model->update($input, $id);

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
        NOARepository $noa,
        HeaderRepository $headers,
        PCCOHeaderRepository $pccoHeaders,
        UnitPurchaseRequestRepository $upr,
        RFQProponentRepository $rfq)
    {
        $noa_modal                  =   $noa->with(['winner','signatory'])->findById($id);

        if($noa_modal->signatory == null)
        {
            return redirect()->back()->with([
                'error'  => "Please select signatory first"
            ]);
        }


        if($noa_modal->upr->mode_of_procurement == 'public_bidding')
        {
            $proponent_awardee  =   $noa_modal->biddingWinner->supplier;
            $bidamount          =   $noa_modal->biddingWinner->bid_amount;
            $data['rfq_date']           =   $noa_modal->awarded_date;

            $data['rfq_number']         =   "";
        }
        else
        {
            $proponent_awardee          =   $noa_modal->winner->supplier;
            $bidamount                  =   $noa_modal->winner->bid_amount;
            $result                     =   $model->findById($noa_modal->canvass_id);

            $rfq_model                  =   $blank->findById($result->rfq_id);
            $data['rfq_date']           =   $rfq_model->transaction_date;

            $data['rfq_number']         =   $rfq_model->rfq_number;

        }
        $upr_model                  =   $upr->with(['unit'])->findById($noa_modal->upr_id);

        $header                     =  $pccoHeaders->findByPCCO($noa_modal->upr->procurement_office);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $data['transaction_date']   =   $noa_modal->awarded_date;
        $data['account_type']        =   $noa_modal->account_type;
        $data['supplier']           =   $proponent_awardee;
        $data['unit']               =   $upr_model->unit->short_code;
        $data['center']             =   $noa_modal->upr->centers->name;
        $data['total_amount']       =   $upr_model->total_amount;
        $data['bid_amount']         =   $bidamount;
        $data['items']              =   $upr_model->items;
        $data['header']             =   $noa_modal->upr->centers;


        $data['signatory']          =   explode('/', $noa_modal->signatory);
        $data['project_name']       =   $upr_model->project_name;

        $pdf = PDF::loadView('forms.noa', ['data' => $data])
            ->setOption('margin-bottom', 30);
            // ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('noa.pdf');
    }

    /**
     * [viewPrint description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrint2(
        $id,
        CanvassingRepository $model,
        BlankRFQRepository $blank,
        NOARepository $noa,
        HeaderRepository $headers,
        PCCOHeaderRepository $pccoHeaders,
        UnitPurchaseRequestRepository $upr,
        RFQProponentRepository $rfq)
    {
        $noa_modal                  =   $noa->with(['winner','signatory'])->findById($id);

        if($noa_modal->signatory == null)
        {
            return redirect()->back()->with([
                'error'  => "Please select signatory first"
            ]);
        }


        if($noa_modal->upr->mode_of_procurement == 'public_bidding')
        {
            $proponent_awardee  =   $noa_modal->biddingWinner->supplier;
            $bidamount          =   $noa_modal->biddingWinner->bid_amount;
            $data['rfq_date']           =   $noa_modal->awarded_date;

            $data['rfq_number']         =   "";
        }
        else
        {
            $proponent_awardee          =   $noa_modal->winner->supplier;
            $bidamount                  =   $noa_modal->winner->bid_amount;
            $result                     =   $model->findById($noa_modal->canvass_id);

            $rfq_model                  =   $blank->findById($result->rfq_id);
            $data['rfq_date']           =   $rfq_model->transaction_date;

            $data['rfq_number']         =   $rfq_model->rfq_number;

        }
        $upr_model                  =   $upr->with(['unit'])->findById($noa_modal->upr_id);

        $header                     =  $pccoHeaders->findByPCCO($noa_modal->upr->procurement_office);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $data['transaction_date']   =   $noa_modal->awarded_date;
        $data['account_type']        =   $noa_modal->account_type;
        $data['supplier']           =   $proponent_awardee;
        $data['unit']               =   $upr_model->unit->short_code;
        $data['center']             =   $noa_modal->upr->centers->name;
        $data['total_amount']       =   $upr_model->total_amount;
        $data['bid_amount']         =   $bidamount;
        $data['items']              =   $upr_model->items;
        $data['header']             =   $noa_modal->upr->centers;


        $data['signatory']          =   explode('/', $noa_modal->signatory);
        $data['project_name']       =   $upr_model->project_name;

        $pdf = PDF::loadView('forms.noa2', ['data' => $data])
            ->setOption('margin-bottom', 30);
            // ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('noa.pdf');
    }

    /**
     * [downloadCopy description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function downloadCopy($id, NOARepository $model)
    {
        $result         = $model->findById($id);

        $directory      =   storage_path("app/noa-attachments/".$result->file);

        if(!file_exists($directory))
        {
            return 'Sorry. File does not exists.';
        }

        return response()->download($directory);
    }

    /**
     * [viewLogs description]
     *
     * @param  [type]             $id    [description]
     * @param  BlankRFQRepository $model [description]
     * @return [type]                    [description]
     */
    public function viewLogs($id, NOARepository $model, AuditLogRepository $logs)
    {

        $modelType  =   'Revlv\Procurements\NoticeOfAward\NOAEloquent';
        $result     =   $logs->findByModelAndId($modelType, $id);
        $data_model =   $model->findById($id);

        return $this->view('modules.procurements.noa.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'model'         =>  $data_model,
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('Notice of Award', 'procurements.noa.show', $data_model->id),
                new Breadcrumb('Update'),
            ]
        ]);
    }
}
