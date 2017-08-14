<?php

namespace Revlv\Controllers\Biddings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;
use Carbon\Carbon;
use Validator;
use \App\Support\Breadcrumb;

use Revlv\Biddings\BidOpening\BidOpeningRepository;
use Revlv\Biddings\BidOpening\BidOpeningRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\Suppliers\SupplierRepository;
use \Revlv\Settings\Holidays\HolidayRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Users\Logs\UserLogRepository;
use \Revlv\Users\UserRepository;

class BidOpeningController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "biddings.bid-openings.";

    /**
     * [$upr description]
     *
     * @var [type]
     */
    protected $upr;
    protected $holidays;
    protected $suppliers;
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
    public function getDatatable(BidOpeningRepository $model)
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
        return $this->view('modules.biddings.bid-openings.index',[
            'createRoute'   =>  $this->baseUrl."create",
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb('SOBE')
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->view('modules.biddings.bid-openings.create',[
            'indexRoute'    =>  $this->baseUrl.'index',
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
        BidOpeningRequest $request,
        BidOpeningRepository $model,
        HolidayRepository $holidays,
        UnitPurchaseRequestRepository $upr)
    {
        $upr_model              =   $upr->findById($request->upr_id);
        $pre_bid                =   Carbon::createFromFormat('Y-m-d',$upr_model->bid_conference->transaction_date);
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->op_transaction_date);
        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $day_delayed            =   $pre_bid->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $cd                     =   $upr_model->date_prepared->diffInDays($transaction_date);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed > 0)
        {
            $day_delayed            =   $day_delayed - 1;
        }

        $validator = Validator::make($request->all(),[
            'op_transaction_date'  =>  'required_without:return_date|after_or_equal:'.$upr_model->bid_conference->transaction_date,
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
        $inputs['processed_by']     =   \Sentinel::getUser()->id;
        $inputs['transaction_date'] =   $request->op_transaction_date;
        $inputs['action']           =   $request->action;
        $inputs['remarks']          =   $request->remarks;
        $inputs['upr_number']       =   $upr_model->upr_number;
        $inputs['upr_id']           =   $upr_model->id;
        $inputs['ref_number']       =   $upr_model->ref_number;
        $inputs['days']             =   $wd;

        $result = $model->save($inputs);

        $upr->update([
            'status' => 'SOBE OPEN',
            'next_allowable'=> 1,
            'next_step'     => 'Closed SOBE',
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
        BidOpeningRepository $model)
    {
        $result =   $model->findById($id);

        return $this->view('modules.biddings.bid-openings.show',[
            'data'              =>  $result,
            'indexRoute'        =>  $this->baseUrl.'index',
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb($result->upr_number, 'biddings.unit-purchase-requests.show', $result->upr_id ),
                new Breadcrumb('SOBE')
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
        BidOpeningRepository $model,
        UnitPurchaseRequestRepository $upr)
    {

        $result     =   $model->findById($id);

        return $this->view('modules.biddings.bid-openings.edit',[
            'data'          =>  $result,
            'indexRoute'    =>  $this->baseUrl.'show',
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update', $id],
                    'method'    =>  'PUT',
                    'novalidate'=>  'novalidate'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb('SOBE', 'biddings.bid-openings.show', $result->id),
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
        $id,
        Request $request,
        UserRepository $users,
        UnitPurchaseRequestRepository $upr,
        AuditLogRepository $audits,
        HolidayRepository $holidays,
        UserLogRepository $userLogs,
        BidOpeningRepository $model)
    {
        $result     =   $model->update(['transaction_date' => $request->transaction_date, 'closing_date' => $request->closing_date,'update_remarks' => $request->update_remarks, ], $id);

        $upr_model              =   $result->upr;
        $pre_bid                =   Carbon::createFromFormat('Y-m-d',$upr_model->bid_conference->transaction_date);
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->transaction_date);
        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $day_delayed            =   $pre_bid->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        if($day_delayed < 0)
        {
            $day_delayed            =   $day_delayed - 1;
        }

        if($day_delayed != $result->days)
        {
            $model->update(['days' => $day_delayed], $id);
        }

        $modelType  =   'Revlv\Biddings\BidOpening\BidOpeningEloquent';
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
     * [closed description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function closed($id,
        BidOpeningRepository $model,
        UnitPurchaseRequestRepository $upr)
    {
        $result =   $model->update(['closing_date' => Carbon::now()], $id);
        $transaction_date   =   Carbon::createFromFormat('Y-m-d',$result->transaction_date);
        $upr_model  = $result->upr;

        $upr->update([
            'status' => 'SOBE Closed',
            'next_allowable'=> 1,
            'next_step'     => 'Post Qualification',
            'next_due'      => $transaction_date->addDays(1),
            'last_date'     => $transaction_date,
        ], $upr_model->id);

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
    public function destroy($id, BidOpeningRepository $model)
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
    public function viewLogs($id, BidOpeningRepository $model, AuditLogRepository $logs)
    {

        $modelType  =   'Revlv\Biddings\BidOpening\BidOpeningEloquent';
        $result     =   $logs->findByModelAndId($modelType, $id);
        $data_model =   $model->findById($id);

        return $this->view('modules.biddings.bid-openings.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'model'         =>  $data_model,
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb('SOBE', 'biddings.bid-openings.show', $data_model->id),
                new Breadcrumb('Logs')
            ]
        ]);
    }

    /**
     *
     *
     * @param  Request                       $request [description]
     * @param  PostQualificationRepository   $model   [description]
     * @param  UnitPurchaseRequestRepository $upr     [description]
     * @return [type]                                 [description]
     */
    public function failed(
        Request $request,
        BidOpeningRepository $model,
        UnitPurchaseRequestRepository $upr)
    {

        $this->validate($request,[
            'failed_date'       =>  'required',
            'failed_remarks'    =>  'required'
        ]);

        $result     =   $model->update(['failed_remarks' => $request->failed_remarks, 'failed_date' => $request->failed_date],$request->id);

        $upr_model  =   $result->upr;
        $upr->update(['status' => 'Failed SOBE'], $upr_model->id);

        return redirect()->route($this->baseUrl.'show', $result->id)->with([
            'success'  => "New record has been successfully added."
        ]);
    }
}
