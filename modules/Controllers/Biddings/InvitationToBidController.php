<?php

namespace Revlv\Controllers\Biddings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;
use Carbon\Carbon;
use Validator;
use \App\Support\Breadcrumb;

use Revlv\Biddings\InvitationToBid\InvitationToBidRepository;
use Revlv\Biddings\InvitationToBid\InvitationToBidRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\Suppliers\SupplierRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\Holidays\HolidayRepository;
use \Revlv\Users\Logs\UserLogRepository;
use \Revlv\Users\UserRepository;

class InvitationToBidController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "biddings.itb.";

    /**
     * [$upr description]
     *
     * @var [type]
     */
    protected $upr;
    protected $suppliers;
    protected $audits;
    protected $holidays;
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
    public function getDatatable(InvitationToBidRepository $model)
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
        return $this->view('modules.biddings.itb.index',[
            'createRoute'   =>  $this->baseUrl."create",
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb('Invitation To Bid')
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
        $this->view('modules.biddings.itb.create',[
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
        InvitationToBidRequest $request,
        InvitationToBidRepository $model,
        HolidayRepository $holidays,
        UnitPurchaseRequestRepository $upr)
    {

        $upr_model              =   $upr->findById($request->upr_id);
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->itb_approved_date);
        $document_accept        =   Carbon::createFromFormat('Y-m-d', $upr_model->document_accept->approved_date);
        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $day_delayed            =   $document_accept->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        if($day_delayed != 0)
        $day_delayed            =   $day_delayed - 1;

        $validator = Validator::make($request->all(),[
            'itb_approved_date'  =>  'required',
            'action'             =>  'required_with:remarks',
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
        $inputs     =   [
            'upr_id'            =>  $upr_model->id,
            'upr_number'        =>  $upr_model->upr_number,
            'ref_number'        =>  $upr_model->ref_number,
            'days'              =>  $day_delayed,
            'remarks'           =>  $request->remarks,
            'action'            =>  $request->action,
            'approved_date'     =>  $request->itb_approved_date,
            'transaction_date'  =>  $request->itb_approved_date,
            'approved_by'       =>  \Sentinel::getUser()->id
        ];

        $result = $model->save($inputs);

        $upr->update(['status' => 'ITB Created', 'delay_count' => $day_delayed + $upr_model->delay_count], $upr_model->id);

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
        InvitationToBidRepository $model)
    {
        $result     =   $model->findById($id);

        return $this->view('modules.biddings.itb.show',[
            'data'              =>  $result,
            'indexRoute'        =>  $this->baseUrl.'index',
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb($result->upr_number, 'biddings.unit-purchase-requests.show', $result->upr_id ),
                new Breadcrumb('Invitation To Bid', 'biddings.itb.index'),
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
        InvitationToBidRepository $model,
        UnitPurchaseRequestRepository $upr)
    {

        $result =   $model->findById($id);

        $this->view('modules.biddings.itb.edit',[
            'indexRoute'    =>  $this->baseUrl.'index',
            'data'          =>  $result,
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
        AuditLogRepository $audits,
        UserLogRepository $userLogs,
        UserRepository $users,
        HolidayRepository $holidays,
        UnitPurchaseRequestRepository $upr,
        Request $request,
        InvitationToBidRepository $model)
    {
        $result     =   $model->update(['approved_date' => $request->approved_date, 'update_remarks' => $request->update_remarks], $id);

        $upr_model              =   $result->upr;
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->approved_date);
        $document_accept        =   Carbon::createFromFormat('Y-m-d', $upr_model->document_accept->approved_date);
        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $day_delayed            =   $document_accept->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        if($day_delayed != 0)
        $day_delayed            =   $day_delayed - 1;

        if($day_delayed != $result->days)
        {
            $model->update(['days' => $day_delayed], $id);
        }

        $modelType  =   'Revlv\Biddings\InvitationToBid\InvitationToBidEloquent';
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
    public function destroy($id, InvitationToBidRepository $model)
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
    public function viewLogs($id, InvitationToBidRepository $model, AuditLogRepository $audits)
    {

        $modelType  =   'Revlv\Biddings\InvitationToBid\InvitationToBidEloquent';
        $result     =   $audits->findByModelAndId($modelType, $id);
        $rfq_model  =   $model->findById($id);

        return $this->view('modules.biddings.itb.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'rfq'           =>  $rfq_model,
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb($rfq_model->upr_number, 'biddings.unit-purchase-requests.show', $rfq_model->upr_id ),
                new Breadcrumb('Invitation To Bid'),
                new Breadcrumb('Logs'),
            ]
        ]);
    }

}
