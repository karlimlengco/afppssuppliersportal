<?php

namespace Revlv\Controllers\Biddings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;
use Carbon\Carbon;
use Validator;
use \App\Support\Breadcrumb;
use App\Events\Event;

use Revlv\Biddings\DocumentAcceptance\DocumentAcceptanceRepository;
use Revlv\Biddings\PreProc\PreProcRepository;
use Revlv\Biddings\PreProc\PreProcRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\Suppliers\SupplierRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\Holidays\HolidayRepository;
use \Revlv\Users\Logs\UserLogRepository;
use \Revlv\Users\UserRepository;

class PreProcController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "biddings.preproc.";

    /**
     * [$upr description]
     *
     * @var [type]
     */
    protected $upr;
    protected $docs;
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
    public function getDatatable(PreProcRepository $model)
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
        return $this->view('modules.biddings.preproc.index',[
            'createRoute'   =>  $this->baseUrl."create",
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb('Pre Proc Conference')
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id,  UnitPurchaseRequestRepository $model)
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
        PreProcRequest $request,
        PreProcRepository $model,
        HolidayRepository $holidays,
        UnitPurchaseRequestRepository $upr)
    {
        $upr_model              =   $upr->findById($request->upr_id);
        $doc_accept             =   $upr_model->document_accept;

        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->pre_proc_date);
        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $day_delayed            =   Carbon::createFromFormat('!Y-m-d', $doc_accept->approved_date)->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $cd                     =   Carbon::createFromFormat('!Y-m-d', $doc_accept->approved_date)->diffInDays($transaction_date);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;


        if($day_delayed >= 1)
        $day_delayed            =   $day_delayed - 1;

        $validator = Validator::make($request->all(),[
            'pre_proc_date'  =>  'required|after_or_equal:'.$upr_model->document_accept->approved_date,
        ]);

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
        $inputs['transaction_date'] =   $request->pre_proc_date;
        $inputs['days']             =   $wd;

        $result = $model->save($inputs);

        if($request->resched_date == null)
        {
            $upr_result = $upr->update([
                'status'        => 'PreProc Conference',
                'next_allowable'=> 7,
                'next_step'     => 'Invitation To Bid',
                'next_due'      => $transaction_date->addDays(7),
                'last_date'     => $transaction_date,
                'delay_count'   => $wd,
                'calendar_days' => $cd + $result->upr->calendar_days,
                'last_action'   => $request->action,
                'last_remarks'  => $request->remarks
            ], $result->upr_id);

            event(new Event($upr_result, $upr_result->upr_number." PreProc Conference"));
        }
        else
        {
            event(new Event($result->upr, $result->upr->upr_number." Re-Sched PreProc Conference"));
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
        PreProcRepository $model)
    {
        $result =   $model->findById($id);

        return $this->view('modules.biddings.preproc.show',[
            'data'              =>  $result,
            'indexRoute'        =>  $this->baseUrl.'index',
            'editRoute'         =>  $this->baseUrl.'edit',
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb($result->upr_number, 'biddings.unit-purchase-requests.show', $result->upr_id ),
                new Breadcrumb('PreProc Conference'),
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
        PreProcRepository $model,
        UnitPurchaseRequestRepository $upr)
    {
        $result =   $model->findById($id);

        $this->view('modules.biddings.preproc.edit',[
            'indexRoute'    =>  $this->baseUrl.'index',
            'data'          =>  $result,
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update', $id],
                    'method'    =>  'PUT',
                    'novalidate'=>  'novalidate'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb($result->upr_number, 'biddings.unit-purchase-requests.show', $result->upr_id ),
                new Breadcrumb('PreProc Conference'),
                new Breadcrumb('Update'),
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
        PreProcRequest $request,
        AuditLogRepository $audits,
        UserLogRepository $userLogs,
        UserRepository $users,
        HolidayRepository $holidays,
        UnitPurchaseRequestRepository $upr,
        PreProcRepository $model)
    {
        $result = $model->update($request->getData(), $id);

        $upr_model              =   $result->upr;
        $doc_accept             =   $upr_model->document_accept;
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->pre_proc_date);
        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $day_delayed            =   Carbon::createFromFormat('!Y-m-d', $doc_accept->approved_date)->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $cd                     =   Carbon::createFromFormat('!Y-m-d', $doc_accept->approved_date)->diffInDays($transaction_date);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed != 0)
        $day_delayed            =   $day_delayed - 1;

        if($wd != $result->days)
        {
            $model->update(['days' => $wd], $id);
        }

        $modelType  =   'Revlv\Biddings\DocumentAcceptance\DocumentAcceptanceEloquent';
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
    public function destroy($id, PreProcRepository $model)
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
    public function viewLogs($id, PreProcRepository $model, AuditLogRepository $audits)
    {

        $modelType  =   'Revlv\Biddings\PreProc\PreProcEloquent';
        $result     =   $audits->findByModelAndId($modelType, $id);
        $rfq_model  =   $model->findById($id);

        return $this->view('modules.biddings.preproc.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'rfq'           =>  $rfq_model,
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb($rfq_model->upr_number, 'biddings.unit-purchase-requests.show', $rfq_model->upr_id ),
                new Breadcrumb('PreProc Conference'),
                new Breadcrumb('Logs'),
            ]
        ]);
    }
}
