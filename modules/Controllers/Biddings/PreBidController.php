<?php

namespace Revlv\Controllers\Biddings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;
use Carbon\Carbon;
use \App\Support\Breadcrumb;
use Validator;
use App\Events\Event;

use Revlv\Biddings\PreBid\PreBidRepository;
use Revlv\Biddings\PreBid\PreBidRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\Suppliers\SupplierRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\Holidays\HolidayRepository;
use \Revlv\Users\Logs\UserLogRepository;
use \Revlv\Users\UserRepository;


class PreBidController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "biddings.pre-bids.";

    /**
     * [$upr description]
     *
     * @var [type]
     */
    protected $upr;
    protected $suppliers;
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
    public function getDatatable(PreBidRepository $model)
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
        return $this->view('modules.biddings.pre-bids.index',[
            'createRoute'   =>  $this->baseUrl."create",
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb('Pre Bid Conference')
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id, UnitPurchaseRequestRepository $model)
    {
        $result =   $model->findById($id);

        // if($result->bid_issuance == null)
        // {
        //     return redirect()
        //                 ->back()
        //                 ->with(['error' => 'No Bid Issuance. Click Option and add Bid Issuance'])
        //                 ->withInput();
        // }

        $this->view('modules.biddings.pre-bids.create',[
            'indexRoute'    =>  $this->baseUrl.'index',
            'id'            =>  $id,
            'modelConfig'   =>  [
                'store' =>  [
                    'route'     =>  $this->baseUrl.'store'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb($result->upr_number, 'biddings.unit-purchase-requests.show', $result->id ),
                new Breadcrumb('Pre Bid Conference')
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
        PreBidRepository $model,
        UnitPurchaseRequestRepository $upr)
    {

        $this->validate($request,[
            'failed_remarks'    =>  'required'
        ]);

        $result     =   $model->update(['failed_remarks' => $request->failed_remarks], $request->id);
        $upr_model  =   $result->upr;

        $upr->update(['status' => 'Failed Pre Bid'], $upr_model->id);

        return redirect()->route($this->baseUrl.'show', $result->id)->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        PreBidRequest $request,
        PreBidRepository $model,
        HolidayRepository $holidays,
        UnitPurchaseRequestRepository $upr)
    {
        $upr_model              =   $upr->findById($request->upr_id);
        $philgeps               =   Carbon::createFromFormat('Y-m-d',$upr_model->philgeps->transaction_date);
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->transaction_date);
        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $day_delayed            =   $philgeps->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $cd                     =   $upr_model->date_prepared->diffInDays($transaction_date);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed >= 1)
        $day_delayed            =   $day_delayed - 1;

        $validator = Validator::make($request->all(),[
            'transaction_date'  =>  'required_without:return_date|after_or_equal:'.$upr_model->philgeps->transaction_date,
            'bid_opening_date'  =>  'after:transaction_date',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if($request->resched_date == null)
            {
                if ( $request->get('remarks') == null && $day_delayed >= 1) {
                    $validator->errors()->add('remarks', 'This field is required when your process is delay');
                }
                if ( $request->get('action') == null && $day_delayed >= 1) {
                    $validator->errors()->add('action', 'This field is required when your process is delay');
                }
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Please Check Your Fields.'])
                        ->withErrors($validator)
                        ->withInput();
        }
        $inputs                     =   $request->getData();
        $inputs['processed_by']     =   \Sentinel::getUser()->id;
        $inputs['upr_number']       =   $upr_model->upr_number;
        $inputs['ref_number']       =   $upr_model->ref_number;
        $inputs['days']             =   $wd;

        $result = $model->save($inputs);

        if($request->resched_date == null)
        {
            $upr_result  = $upr->update([
                'status' => 'Pre Bid Conference',
                'next_allowable'=> 1,
                'next_step'     => 'SOBE',
                'next_due'      => $transaction_date->addDays(1),
                'last_date'     => $transaction_date,
                'processed_by'  => \Sentinel::getUser()->id,
                'delay_count'   => $wd,
                'calendar_days' => $cd + $result->upr->calendar_days,
                'last_action'   => $request->action,
                'last_remarks'  => $request->remarks
            ], $result->upr_id);

            event(new Event($upr_result, $upr_result->ref_number." Pre Bid Conference"));
        }
        else
        {
            event(new Event($result->upr, $result->upr->ref_number." Re-Sched Pre Bid Conference"));
        }

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
        PreBidRepository $model)
    {
        $result =   $model->findById($id);

        return $this->view('modules.biddings.pre-bids.show',[
            'data'              =>  $result,
            'indexRoute'        =>  $this->baseUrl.'index',
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb($result->upr_number, 'biddings.unit-purchase-requests.show', $result->upr_id ),
                new Breadcrumb('Pre Bid Conference')
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
        PreBidRepository $model,
        UnitPurchaseRequestRepository $upr)
    {
        $result     =   $model->findById($id);

        return $this->view('modules.biddings.pre-bids.edit',[
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
                new Breadcrumb('Pre Bid Conference', 'biddings.pre-bids.show', $result->id),
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
        PreBidRequest $request,
        UserRepository $users,
        UnitPurchaseRequestRepository $upr,
        AuditLogRepository $audits,
        HolidayRepository $holidays,
        UserLogRepository $userLogs,
        PreBidRepository $model)
    {
        $result =   $model->update($request->getData(), $id);

        $upr_model              =   $result->upr;
        $philgeps               =   Carbon::createFromFormat('Y-m-d',$upr_model->philgeps->transaction_date);
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->transaction_date);
        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $day_delayed            =   $philgeps->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        if($day_delayed != 0)
        $day_delayed            =   $day_delayed - 1;

        if($day_delayed != $result->days)
        {
            $model->update(['days' => $day_delayed], $id);
        }

        $modelType  =   'Revlv\Biddings\PreBid\PreBidEloquent';
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, PreBidRepository $model)
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
    public function viewLogs($id, PreBidRepository $model, AuditLogRepository $logs)
    {

        $modelType  =   'Revlv\Biddings\PreBid\PreBidEloquent';
        $result     =   $logs->findByModelAndId($modelType, $id);
        $data_model =   $model->findById($id);

        return $this->view('modules.biddings.pre-bids.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'model'         =>  $data_model,
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb('Pre Bid Conference', 'biddings.pre-bids.show', $data_model->id),
                new Breadcrumb('Logs')
            ]
        ]);
    }

}
