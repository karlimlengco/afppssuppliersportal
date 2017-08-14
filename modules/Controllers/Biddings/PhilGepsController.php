<?php

namespace Revlv\Controllers\Biddings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;
use Carbon\Carbon;
use \App\Support\Breadcrumb;
use Validator;

use \Revlv\Procurements\PhilGepsPosting\PhilGepsPostingRepository;
use \Revlv\Procurements\PhilGepsPosting\PhilGepsPostingRequest;
use \Revlv\Procurements\PhilGepsPosting\UpdateRequest;
use \Revlv\Procurements\PhilGepsPosting\BidUpdateRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Settings\Holidays\HolidayRepository;
use \Revlv\Users\Logs\UserLogRepository;
use \Revlv\Users\UserRepository;

class PhilGepsController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "biddings.philgeps.";

    /**
     * [$upr description]
     *
     * @var [type]
     */
    protected $upr;
    protected $holidays;
    protected $audits;
    protected $users;
    protected $userLogs;

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
    public function getDatatable(PhilGepsPostingRepository $model)
    {
        return $model->getDatatable('public');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.biddings.philgeps.index',[
            'createRoute'   =>  $this->baseUrl."create",
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb('Philgeps Posting')
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
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
        Request $request,
        PhilGepsPostingRepository $model,
        HolidayRepository $holidays,
        UnitPurchaseRequestRepository $upr)
    {
        $upr_model              =   $upr->findById($request->upr_id);
        $invitation             =   Carbon::createFromFormat('Y-m-d',$upr_model->itb->approved_date);
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->pp_transaction_date);
        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $day_delayed            =   $invitation->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $cd                     =   $invitation->diffInDays($transaction_date);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed > 1)
        $day_delayed            =   $day_delayed - 1;

        $validator = Validator::make($request->all(),[
            'pp_transaction_date'        =>  'required|after_or_equal:'.$upr_model->itb->approved_date,
            'pp_philgeps_posting'        =>  'required|after_or_equal:'.$upr_model->itb->approved_date,
            'philgeps_number'            =>  'required',
            'status'                     =>  'required',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('remarks') == null && $day_delayed >= 1) {
                $validator->errors()->add('remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('action') == null && $day_delayed >= 1) {
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

        $inputs                  =  [
            'transaction_date'   =>  $request->pp_transaction_date,
            'philgeps_posting'   =>  $request->pp_philgeps_posting,
            'philgeps_number'    =>  $request->philgeps_number,
            'remarks'            =>  $request->remarks,
            'status'             =>  $request->status,
            'action'             =>  $request->action,
            'newspaper'          =>  $request->newspaper,
        ];
        $inputs['upr_id']           =   $upr_model->id;
        $inputs['upr_number']       =   $upr_model->upr_number;
        $inputs['days']             =   $wd;

        $result = $model->save($inputs);

        $status  = 'Philgeps Approved';
        if($request->status == 0)
        {
            $status  = 'Philgeps Need Repost';
        }

        $upr->update([
            'status'        => $status,
            'next_allowable'=> 1,
            'next_step'     => 'Pre Bid',
            'next_due'      => $transaction_date->addDays(1),
            'last_date'     => $transaction_date,
            'processed_by'  => \Sentinel::getUser()->id,
            'delay_count'   => $wd,
            'calendar_days' => $cd + $result->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
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
        PhilGepsPostingRepository $model)
    {
        $result =   $model->findById($id);

        return $this->view('modules.biddings.philgeps.show',[
            'data'              =>  $result,
            'indexRoute'        =>  $this->baseUrl.'index',
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb($result->upr_number, 'biddings.unit-purchase-requests.show', $result->upr_id ),
                new Breadcrumb('PhilGeps Posting')
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
        PhilGepsPostingRepository $model,
        UnitPurchaseRequestRepository $upr)
    {
        $result     =   $model->findById($id);

        return $this->view('modules.biddings.philgeps.edit',[
            'data'          =>  $result,
            'indexRoute'    =>  $this->baseUrl.'show',
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update', $id],
                    'method'    =>  'PUT',
                    'novalidate'=>  'novalidate'
                ],
                'destroy'   => [
                    'route' => [$this->baseUrl.'destroy',$id],
                    'method'=> 'DELETE'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb($result->philgeps_number, 'biddings.philgeps.show', $result->id),
                new Breadcrumb('Update')
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
        BidUpdateRequest $request,
        $id,
        \Revlv\Users\UserRepository $users,
        UnitPurchaseRequestRepository $upr,
        BlankRFQRepository $rfq,
        AuditLogRepository $audits,
        HolidayRepository $holidays,
        UserLogRepository $userLogs,
        PhilGepsPostingRepository $model)
    {
        $old                    =   $model->findById($id);
        $result                 =   $model->update($request->getData(), $id);

        if($result->rfq_id)
        {

            $rfq_model              =   $rfq->findById($result->rfq_id);
            $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->transaction_date);

            if($invitation = $rfq_model->invitations)
            {
                $ispq_transaction_date   = Carbon::createFromFormat('Y-m-d', $invitation->ispq->transaction_date);
            }
            else
            {
                $ispq_transaction_date   = $rfq_model->completed_at;
            }

            $holiday_lists          =   $holidays->lists('id','holiday_date');

            $day_delayed            =   $ispq_transaction_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
                return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
            }, $transaction_date);
        }
        else
        {
            $upr_model              =   $upr->findById($result->upr_id);
            $invitation             =   Carbon::createFromFormat('Y-m-d',$upr_model->itb->approved_date);
            $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->transaction_date);
            $holiday_lists          =   $holidays->lists('id','holiday_date');

            $day_delayed            =   $invitation->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
                return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
            }, $transaction_date);

            $validator = Validator::make($request->all(),[
                'transaction_date'        =>  'required|after_or_equal:'.$upr_model->itb->approved_date,
                'philgeps_posting'        =>  'required|after_or_equal:'.$upr_model->itb->approved_date,
                'philgeps_number'         =>  'required',
            ]);

            if ($validator->fails()){
                return redirect()
                            ->back()
                            ->with(['error' => 'Please Check Your Fields.'])
                            ->withErrors($validator)
                            ->withInput();
            }
        }

        $cd                     =   $invitation->diffInDays($transaction_date);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed != 0)
        {
            $day_delayed            =   $day_delayed - 1;
        }

        if($wd != $result->days)
        {
            $model->update(['days' => $wd], $id);
        }

        $modelType  =   'Revlv\Procurements\PhilGepsPosting\PhilGepsPostingEloquent';
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

        if($old->status == 1 && $result->status == 0)
        {
            $upr->update([
            'status'        =>  'Philgeps Need Repost',
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr->id);
        }

        return redirect()->route($this->baseUrl.'edit', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, PhilGepsPostingRepository $model)
    {
        $model->delete($id);

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }

    /**
     * [viewLogs description]
     *
     * @param  [type]             $id    [description]
     * @param  BlankRFQRepository $model [description]
     * @return [type]                    [description]
     */
    public function viewLogs($id, PhilGepsPostingRepository $model, AuditLogRepository $logs)
    {

        $modelType  =   'Revlv\Procurements\PhilGepsPosting\PhilGepsPostingEloquent';
        $result     =   $logs->findByModelAndId($modelType, $id);
        $data_model =   $model->findById($id);

        return $this->view('modules.biddings.philgeps.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'model'         =>  $data_model,
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb($data_model->philgeps_number, 'biddings.philgeps.show', $data_model->id),
                new Breadcrumb('Logs')
            ]
        ]);
    }
}
