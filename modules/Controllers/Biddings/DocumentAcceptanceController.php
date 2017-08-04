<?php

namespace Revlv\Controllers\Biddings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;
use Carbon\Carbon;
use Validator;
use \App\Support\Breadcrumb;

use Revlv\Biddings\DocumentAcceptance\DocumentAcceptanceRepository;
use Revlv\Biddings\DocumentAcceptance\DocumentAcceptanceRequest;
use Revlv\Biddings\DocumentAcceptance\UpdateRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\Suppliers\SupplierRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\Holidays\HolidayRepository;
use Revlv\Settings\BacSec\BacSecRepository;
use \Revlv\Users\Logs\UserLogRepository;
use \Revlv\Users\UserRepository;

class DocumentAcceptanceController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "biddings.document-acceptance.";

    /**
     * [$upr description]
     *
     * @var [type]
     */
    protected $upr;
    protected $suppliers;
    protected $holidays;
    protected $audits;
    protected $bacs;
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
    public function getDatatable(DocumentAcceptanceRepository $model)
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
        return $this->view('modules.biddings.document-acceptance.index',[
            'createRoute'   =>  $this->baseUrl."create",
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb('Document Acceptance')
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id, BacSecRepository $bacs, UnitPurchaseRequestRepository $model)
    {

        $result         =   $model->findById($id);
        $bac_lists      =   $bacs->lists('id', 'name');
        $this->view('modules.biddings.document-acceptance.create',[
            'indexRoute'    =>  $this->baseUrl.'index',
            'id'            =>  $id,
            'bac_lists'     =>  $bac_lists,
            'modelConfig'   =>  [
                'store' =>  [
                    'route'     =>  $this->baseUrl.'store'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb($result->upr_number, 'biddings.unit-purchase-requests.show', $result->upr_id ),
                new Breadcrumb('Document Acceptancce'),
                new Breadcrumb('Create'),
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
        DocumentAcceptanceRequest $request,
        DocumentAcceptanceRepository $model,
        HolidayRepository $holidays,
        UnitPurchaseRequestRepository $upr)
    {
        $upr_model              =   $upr->findById($request->upr_id);
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->transaction_date);
        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $day_delayed            =   $upr_model->date_prepared->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $cd                     =   $upr_model->date_prepared->diffInDays($transaction_date);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed >= 1)
        $day_delayed            =   $day_delayed - 1;

        $validator = Validator::make($request->all(),[
            'transaction_date'  =>  'required',
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
                        ->with(['error' => 'Your process is delay. Please add remarks to continue.'])
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
            $upr->update([
                'status' => 'Document Accepted',
                'next_allowable'=> 1,
                'next_step'     => 'Invitation To Bid',
                'state'         => 'On-Going',
                'next_due'      => $transaction_date->addDays(1),
                'last_date'     => $transaction_date,
                'date_processed'=> \Carbon\Carbon::now(),
                'processed_by'  => \Sentinel::getUser()->id,
                'delay_count'   => $day_delayed,
                'calendar_days' => $cd,
                'last_action'   => $request->action,
                'last_remarks'  => $request->remarks
            ], $result->upr_id);
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
        DocumentAcceptanceRepository $model)
    {
        $result =   $model->findById($id);

        return $this->view('modules.biddings.document-acceptance.show',[
            'data'              =>  $result,
            'indexRoute'        =>  $this->baseUrl.'index',
            'editRoute'        =>  $this->baseUrl.'edit',
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb($result->upr_number, 'biddings.unit-purchase-requests.show', $result->upr_id ),
                new Breadcrumb('Document Acceptancce'),
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
        DocumentAcceptanceRepository $model,
        BacSecRepository $bacs,
        UnitPurchaseRequestRepository $upr)
    {
        $result =   $model->findById($id);

        $bac_lists      =   $bacs->lists('id', 'name');
        $this->view('modules.biddings.document-acceptance.edit',[
            'indexRoute'    =>  $this->baseUrl.'index',
            'data'          =>  $result,
            'bac_lists'     =>  $bac_lists,
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update', $id],
                    'method'    =>  'PUT'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb($result->upr_number, 'biddings.unit-purchase-requests.show', $result->upr_id ),
                new Breadcrumb('Document Acceptancce'),
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
        UpdateRequest $request,
        AuditLogRepository $audits,
        UserLogRepository $userLogs,
        UserRepository $users,
        HolidayRepository $holidays,
        UnitPurchaseRequestRepository $upr,
        DocumentAcceptanceRepository $model)
    {
        $result = $model->update($request->getData(), $id);

        $upr_model              =   $result->upr;
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->transaction_date);
        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $day_delayed            =   $upr_model->date_prepared->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        if($day_delayed != 0)
        $day_delayed            =   $day_delayed - 1;

        if($day_delayed != $result->days)
        {
            $model->update(['days' => $day_delayed], $id);
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
    public function destroy($id, DocumentAcceptanceRepository $model)
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
    public function viewLogs($id, DocumentAcceptanceRepository $model, AuditLogRepository $audits)
    {

        $modelType  =   'Revlv\Biddings\DocumentAcceptance\DocumentAcceptanceEloquent';
        $result     =   $audits->findByModelAndId($modelType, $id);
        $rfq_model  =   $model->findById($id);

        return $this->view('modules.biddings.document-acceptance.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'rfq'           =>  $rfq_model,
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb($rfq_model->upr_number, 'biddings.unit-purchase-requests.show', $rfq_model->upr_id ),
                new Breadcrumb('Document Acceptancce'),
                new Breadcrumb('Logs'),
            ]
        ]);
    }
}
