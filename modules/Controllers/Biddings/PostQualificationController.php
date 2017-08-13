<?php

namespace Revlv\Controllers\Biddings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;
use \App\Support\Breadcrumb;
use Carbon\Carbon;
use Validator;

use Revlv\Biddings\PostQualification\PostQualificationRepository;
use Revlv\Biddings\PostQualification\PostQualificationRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\Suppliers\SupplierRepository;
use \Revlv\Settings\Holidays\HolidayRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Users\Logs\UserLogRepository;
use \Revlv\Users\UserRepository;


class PostQualificationController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "biddings.post-qualifications.";

    /**
     * [$upr description]
     *
     * @var [type]
     */
    protected $upr;
    protected $holidays;
    protected $suppliers;
    protected $signatories;
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
    public function getDatatable(PostQualificationRepository $model)
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
        return $this->view('modules.biddings.post-qualifications.index',[
            'createRoute'   =>  $this->baseUrl."create",
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb('Post Qualifications'),
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
        $this->view('modules.biddings.post-qualifications.create',[
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
        PostQualificationRequest $request,
        PostQualificationRepository $model,
        HolidayRepository $holidays,
        UnitPurchaseRequestRepository $upr)
    {
        $upr_model              =   $upr->findById($request->upr_id);
        $bid_open                =   Carbon::createFromFormat('Y-m-d',$upr_model->bid_open->closing_date);
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->pq_transaction_date);

        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $day_delayed            =   $bid_open->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $cd                     =   $upr_model->date_prepared->diffInDays($transaction_date);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed >= 1)
        {
            $day_delayed            =   $day_delayed - 1;
        }

        $validator = Validator::make($request->all(),[
            'action'                =>  'required_with:remarks',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('remarks') == null && $day_delayed >= 1) {
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

        $inputs['processed_by']     =   \Sentinel::getUser()->id;
        $inputs['transaction_date'] =   $request->pq_transaction_date;
        $inputs['action']           =   $request->action;
        $inputs['remarks']          =   $request->remarks;
        $inputs['upr_number']       =   $upr_model->upr_number;
        $inputs['upr_id']           =   $upr_model->id;
        $inputs['ref_number']       =   $upr_model->ref_number;
        $inputs['days']             =   $wd;

        $result = $model->save($inputs);

        $upr->update([
            'status' => 'Post Qualification',
            'next_allowable'=> 2,
            'next_step'     => 'Prepare NOA',
            'next_due'      => $transaction_date->addDays(2),
            'last_date'     => $transaction_date,
            'date_processed'=> \Carbon\Carbon::now(),
            'processed_by'  => \Sentinel::getUser()->id,
            'delay_count'   => $day_delayed,
            'calendar_days' => $cd,
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
        SignatoryRepository $signatories,
        PostQualificationRepository $model)
    {
        $result         =   $model->findById($id);
        $signatory_lists=   $signatories->lists('id', 'name');

        return $this->view('modules.biddings.post-qualifications.show',[
            'data'              =>  $result,
            'current_signs'     =>  $signatory_lists,
            'indexRoute'        =>  $this->baseUrl.'index',
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb($result->upr_number, 'biddings.unit-purchase-requests.show', $result->upr_id ),
                new Breadcrumb('Post Qualification', 'biddings.post-qualifications.index'),
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
        PostQualificationRepository $model,
        UnitPurchaseRequestRepository $upr)
    {
        $result     =   $model->findById($id);

        return $this->view('modules.biddings.post-qualifications.edit',[
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
                new Breadcrumb('Post Qualification', 'biddings.post-qualifications.show', $result->id),
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
        PostQualificationRepository $model)
    {
        $result =   $model->update(['update_remarks' => $request->update_remarks, 'transaction_date' => $request->transaction_date], $id);

        $upr_model              =   $result->upr;
        $bid_open               =   Carbon::createFromFormat('Y-m-d',$upr_model->bid_open->closing_date);
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->transaction_date);

        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $day_delayed            =   $bid_open->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
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

        $modelType  =   'Revlv\Biddings\PostQualification\PostQualificationEloquent';
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
     *
     *
     * @param  Request                       $request [description]
     * @param  PostQualificationRepository   $model   [description]
     * @param  UnitPurchaseRequestRepository $upr     [description]
     * @return [type]                                 [description]
     */
    public function failed(
        Request $request,
        PostQualificationRepository $model,
        UnitPurchaseRequestRepository $upr)
    {

        $this->validate($request,[
            'failed_remarks'    =>  'required'
        ]);

        $result     =   $model->update(['failed_remarks' => $request->failed_remarks, 'is_failed' => 1],$request->id);

        $upr_model  =   $result->upr;
        $upr->update(['status' => 'Failed Post Qualification'], $upr_model->id);

        return redirect()->route($this->baseUrl.'show', $result->id)->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, PostQualificationRepository $model)
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
    public function viewLogs($id, PostQualificationRepository $model, AuditLogRepository $logs)
    {

        $modelType  =   'Revlv\Biddings\PostQualification\PostQualificationEloquent';
        $result     =   $logs->findByModelAndId($modelType, $id);
        $data_model =   $model->findById($id);

        return $this->view('modules.biddings.post-qualifications.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'model'         =>  $data_model,
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb('Post Qualification', 'biddings.post-qualifications.show', $data_model->id),
                new Breadcrumb('Logs')
            ]
        ]);
    }

}
