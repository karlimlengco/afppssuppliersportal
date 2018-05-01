<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use Carbon\Carbon;
use Validator;
use App\Events\Event;
use \App\Support\Breadcrumb;


use \Revlv\Settings\Forms\Voucher\VoucherRepository as VoucherForm;
use \Revlv\Procurements\Vouchers\VoucherRepository;
use \Revlv\Procurements\Vouchers\VoucherRequest;
use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\NoticeOfAward\NOARepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\Banks\BankRepository;
use \Revlv\Settings\Holidays\HolidayRepository;
use \Revlv\Users\Logs\UserLogRepository;
use \Revlv\Users\UserRepository;
use \Revlv\Settings\Forms\Header\HeaderRepository;

class VoucherController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "procurements.vouchers.";

    /**
     * [$upr description]
     *
     * @var [type]
     */
    protected $upr;
    protected $headers;
    protected $noa;
    protected $rfq;
    protected $audits;
    protected $signatories;
    protected $banks;
    protected $holidays;
    protected $users;
    protected $voucherForm;
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
    public function getDatatable(VoucherRepository $model)
    {
        return $model->getDatatable();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, VoucherRepository $model)
    {
        return $this->view('modules.procurements.vouchers.index',[
            'resources'     =>  $model->paginateByRequest(10, $request, 'alternative'),
            'createRoute'   =>  $this->baseUrl."create",
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('Voucher'),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(BlankRFQRepository $rfq)
    {
        $rfq_list   =   $rfq->listsDeliveryAccepted('id', 'rfq_number');

        $this->view('modules.procurements.vouchers.create',[
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VoucherRequest $request, VoucherRepository $model, BlankRFQRepository $rfq, UnitPurchaseRequestRepository $upr, HolidayRepository $holidays)
    {
        $rfq_model              =   $upr->findById($request->rfq_id);

        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('voucher_transaction_date') );
        $diir_date              =   Carbon::createFromFormat('!Y-m-d', $rfq_model->diir->closed_date );
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
            'amount'                         => 'required',
            'voucher_transaction_date'       => 'required|after_or_equal:'. $diir_date->format('Y-m-d'),
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

        $inputs                     =   $request->getData();
        $inputs['rfq_number']       =   $rfq_model->rfq_number;
        $inputs['transaction_date'] =   $request->voucher_transaction_date;
        $inputs['remarks']          =   $request->remarks;
        $inputs['action']           =   $request->action;
        $inputs['days']             =   $wd;
        $inputs['upr_number']       =   $rfq_model->upr_number;
        $inputs['upr_id']           =   $rfq_model->id;
        $inputs['prepared_by']      =   \Sentinel::getUser()->id;

        $result = $model->save($inputs);

        $upr_result  = $upr->update([
            'next_allowable'=> 2,
            'next_step'     => 'Pre Audit Voucher',
            'next_due'      => $transaction_date->addDays(2),
            'last_date'     => $transaction_date,
            'status'        => 'Preparation of DV',
            'delay_count'   => $day_delayed,
            'calendar_days' => $cd + $result->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr_id);

        event(new Event($upr_result, $upr_result->upr_number." Preparation of DV"));

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
    public function show( VoucherRepository $model, $id, SignatoryRepository $signatories, BankRepository $banks)
    {
        $result         =   $model->findById($id);
        $signatory_lists=   $signatories->lists('id', 'name');
        $bank_list      =   $banks->lists('id', 'code');

        $po_term        =   ($result->upr) ? $result->upr->purchase_order->delivery_terms : 0;
        $ntp_date       =   Carbon::createFromFormat('!Y-m-d',$result->upr->ntp->award_accepted_date);

        // dd($ntp_date);
        $penalty_amount = 0;
        foreach($result->upr->delivery_orders as $dr)
        {
            if($ntp_date < $dr->delivery_date)
            {
                $delivery_date                  =   Carbon::createFromFormat('!Y-m-d', $dr->delivery_date);
                $nr_delay                       =   $ntp_date->diffInDays($delivery_date, false);
                if($nr_delay > $po_term)
                {
                    $penalty_day    =   $nr_delay  - $po_term;
                    foreach($dr->items as $item)
                    {
                        $amount =   $item->total_amount;
                        $penalty_amount += (($amount * 0.01) * 0.1) * $penalty_day;
                    }
                }
                // $penalty                        =   (($result->amount * 0.01) * 0.1) * $nr_delay;
            }
        }

        return $this->view('modules.procurements.vouchers.show',[
            'data'              =>  $result,
            'signatory_list'    =>  $signatory_lists,
            'bank_list'         =>  $bank_list  ,
            'penalty_amount'    =>  $penalty_amount  ,
            'indexRoute'        =>  $this->baseUrl.'index',
            'editRoute'         =>  $this->baseUrl.'edit',
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update-signatories', $id],
                    'method'    =>  'PUT'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb($result->upr_number, 'procurements.unit-purchase-requests.show', $result->upr_id ),
                new Breadcrumb('Voucher', 'procurements.vouchers.index'),
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, VoucherRepository $model, SignatoryRepository $signatories)
    {
        $result     =   $model->findById($id);
        $signatory_lists=   $signatories->lists('id', 'name');

        return $this->view('modules.procurements.vouchers.edit',[
            'data'              =>  $result,
            'signatory_list'    =>  $signatory_lists,
            'indexRoute'        =>  $this->baseUrl.'show',
            'modelConfig'       =>  [
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
                new Breadcrumb('Alternative'),
                new Breadcrumb('Voucher', 'procurements.vouchers.show',$result->id),
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
        Request $request,
        $id,
        UnitPurchaseRequestRepository $upr,
        AuditLogRepository $audits,
        HolidayRepository $holidays,
        UserLogRepository $userLogs,
        UserRepository $users,
        SignatoryRepository $signatories,
        VoucherRepository $model)
    {
        $this->validate($request, [
            'update_remarks'        =>  'required',
            'transaction_date'      =>  'required',
            'certified_by'          =>  'required',
            'approver_id'           =>  'required',
            'receiver_id'           =>  'required',
            'bir_address'           =>  'required',
            'suppliers_address'           =>  'required',
        ]);

        $voucher_model  =   $model->findById($id);
        // dd($request->all());
        $data   =   [
            // 'amount'                =>  $request->amount,
            'final_tax'             =>  $request->final_tax,
            'expanded_witholding_tax'        =>  $request->expanded_witholding_tax,
            'final_tax_amount'      =>  $request->final_tax_amount,
            'ewt_amount'            =>  $request->ewt_amount,
            'update_remarks'        =>  $request->update_remarks,
            'transaction_date'      =>  $request->transaction_date,
            'payment_release_date'  =>  $request->payment_release_date,
            'payment_received_date' =>  $request->payment_received_date,
            'preaudit_date'         =>  $request->preaudit_date,
            'certify_date'          =>  $request->certify_date,
            'journal_entry_date'    =>  $request->journal_entry_date,
            'approval_date'         =>  $request->approval_date,
            'certified_by'          =>  $request->certified_by,
            'approver_id'           =>  $request->approver_id,
            'receiver_id'           =>  $request->receiver_id,
            'bir_address'           =>  $request->bir_address,
            'suppliers_address'     =>  $request->suppliers_address
        ];

        if($voucher_model->certified_by != $request->certified_by)
        {
            $certifier  =   $signatories->findById($request->certified_by);
            $data['certified_signatory']   =   $certifier->name."/".$certifier->ranks."/".$certifier->branch."/".$certifier->designation;
        }

        if($voucher_model->approver_id != $request->approver_id)
        {
            $approver  =   $signatories->findById($request->approver_id);
            $data['approver_signatory']   =   $approver->name."/".$approver->ranks."/".$approver->branch."/".$approver->designation;
        }

        if($voucher_model->receiver_id != $request->receiver_id)
        {
            $receiver  =   $signatories->findById($request->receiver_id);
            $data['receiver_signatory']   =   $receiver->name."/".$receiver->ranks."/".$receiver->branch."/".$receiver->designation;
        }
        $result                 =   $model->update($data, $id);

        $rfq_model              =   $result->upr;

        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('transaction_date') );
        $diir_date              =   Carbon::createFromFormat('!Y-m-d', $rfq_model->diir->closed_date );
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
            $model->update(['days' => $wd], $id);
        }

        $modelType  =   'Revlv\Procurements\Vouchers\VoucherEloquent';
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

    public function preparePaymentVoucher(
        $id,
        Request $request,
        VoucherRepository $model,
        UnitPurchaseRequestRepository $upr,
        HolidayRepository $holidays)
    {
        $voucher                =   $model->findById($id);
        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('prepare_cheque_date') );
        $diir_date              =   Carbon::createFromFormat('!Y-m-d', $voucher->preaudit_date );
        $cd                     =   $diir_date->diffInDays($transaction_date);

        $day_delayed            =   $diir_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;
        if($day_delayed > 2)
        {
            $day_delayed = $day_delayed - 2;
        }

        $validator = Validator::make($request->all(),[
            'prepare_cheque_date'       => 'required|after_or_equal:'. $diir_date->format('Y-m-d'),
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('prepare_cheque_remarks') == null && $day_delayed > 2) {
                $validator->errors()->add('prepare_cheque_remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('prepare_cheque_action') == null && $day_delayed > 2) {
                $validator->errors()->add('prepare_cheque_action', 'This field is required when your process is delay');
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
            'prepare_cheque_date'     => $request->prepare_cheque_date,
            'prepare_cheque_days'     => $wd,
            'prepare_cheque_action'   => $request->prepare_cheque_action,
            'prepare_cheque_remarks'  => $request->prepare_cheque_remarks,
        ];

        $result = $model->update($inputs, $id);

        $upr_result = $upr->update([
            'next_allowable'=> 1,
            'next_step'     => 'Release LDDAP-ADA',
            'next_due'      => $transaction_date->addDays(1),
            'last_date'     => $transaction_date,
            'status'        => 'Prepare LDDAP-ADA',
            'delay_count'   => $day_delayed,
            'calendar_days' => $cd + $result->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr_id);

        event(new Event($upr_result, $upr_result->upr_number." Completed"));

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [preauditVoucher description]
     *
     * @param  [type]            $id      [description]
     * @param  Request           $request [description]
     * @param  VoucherRepository $model   [description]
     * @return [type]                     [description]
     */
    public function preauditVoucher(
        $id,
        Request $request,
        VoucherRepository $model,
        UnitPurchaseRequestRepository $upr,
        HolidayRepository $holidays)
    {
        $voucher                =   $model->findById($id);
        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('preaudit_date') );
        $diir_date              =   Carbon::createFromFormat('!Y-m-d', $voucher->approval_date );
        $cd                     =   $diir_date->diffInDays($transaction_date);

        $day_delayed            =   $diir_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;
        if($day_delayed > 2)
        {
            $day_delayed = $day_delayed - 2;
        }

        $validator = Validator::make($request->all(),[
            'preaudit_date'       => 'required|after_or_equal:'. $diir_date->format('Y-m-d'),
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('preaudit_remarks') == null && $day_delayed > 2) {
                $validator->errors()->add('preaudit_remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('preaudit_action') == null && $day_delayed > 2) {
                $validator->errors()->add('preaudit_action', 'This field is required when your process is delay');
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
            'preaudit_date'     => $request->preaudit_date,
            'preaudit_days'     => $wd,
            'preaudit_action'  => $request->preaudit_action,
            'preaudit_remarks'  => $request->preaudit_remarks,
        ];

        $result = $model->update($inputs, $id);

        $upr_result = $upr->update([
            'next_allowable'=> 1,
            'next_step'     => 'Prepare LDDAP-ADA',
            'next_due'      => $transaction_date->addDays(1),
            'last_date'     => $transaction_date,
            'status'        => 'Pre-Audit',
            'delay_count'   => $day_delayed,
            'calendar_days' => $cd + $result->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr_id);

        // $upr_result = $upr->update([
        //     'next_allowable'=> 0,
        //     'next_step'     => 'Payment',
        //     'next_due'      => $transaction_date,
        //     'last_date'     => $transaction_date,
        //     'status'        => 'completed',
        //     'state'         => 'completed',
        //     'completed_at'  => $request->preaudit_date,
        //     'delay_count'   => $cd + $result->upr->delay_count,
        //     'days'          => $wd],
        // $result->upr_id);

        event(new Event($upr_result, $upr_result->upr_number." Completed"));

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [approvedVoucher description]
     *
     * @param  [type]            $id      [description]
     * @param  Request           $request [description]
     * @param  VoucherRepository $model   [description]
     * @return [type]                     [description]
     */
    public function approvedVoucher($id,
        Request $request,
        VoucherRepository $model,
        UnitPurchaseRequestRepository $upr,
        HolidayRepository $holidays)
    {
        $voucher                =   $model->findById($id);
        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('approval_date') );
        $diir_date              =   Carbon::createFromFormat('!Y-m-d', $voucher->journal_entry_date );
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
            'approval_date'       => 'required',
        ]);

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
        // Delay
        $inputs     =   [
            'approval_date'     => $request->approval_date,
            'approved_days'     => $wd,
            'approved_remarks'  => $request->approved_remarks,
            'approved_action'  => $request->approved_action,
        ];

        $result=    $model->update($inputs, $id);

        $upr->update([
            'next_allowable'=> 1,
            'next_step'     => 'Preaudit Voucher',
            'next_due'      => $transaction_date->addDays(1),
            'last_date'     => $transaction_date,
            'status'        => 'Sign Box D of DV',
            'delay_count'   => $day_delayed,
            'calendar_days' => $cd + $result->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr_id);


        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [certifyVoucher description]
     *
     * @param  [type]            $id      [description]
     * @param  Request           $request [description]
     * @param  VoucherRepository $model   [description]
     * @return [type]                     [description]
     */
    public function certifyVoucher($id,
        Request $request,
        VoucherRepository $model,
        UnitPurchaseRequestRepository $upr,
        HolidayRepository $holidays)
    {
        $voucher                =   $model->findById($id);
        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('certify_date') );
        $vou_date               =   Carbon::createFromFormat('!Y-m-d', $voucher->transaction_date );
        $cd                     =   $vou_date->diffInDays($transaction_date);

        $day_delayed            =   $vou_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;
        if($day_delayed > 1)
        {
            $day_delayed = $day_delayed - 1;
        }


        $validator = Validator::make($request->all(),[
            'certify_date'                      =>'required',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('certify_remarks') == null && $day_delayed > 1) {
                $validator->errors()->add('certify_remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('certify_action') == null && $day_delayed > 1) {
                $validator->errors()->add('certify_action', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Please Check Your Fields.'])
                        ->withErrors($validator)
                        ->withInput();
        }
        $inputs =   [
            'certify_date'                      => $request->certify_date,
            'is_cash_avail'                     => $request->is_cash_avail,
            'subject_to_authority_to_debit_acc' => $request->subject_to_authority_to_debit_acc,
            'documents_completed'               => $request->documents_completed,
            'certify_remarks'                   => $request->certify_remarks,
            'certify_action'                    => $request->certify_action,
            'certify_days'                      => $wd,
        ];

        $result =   $model->update($inputs, $id);

        $upr->update([
            'next_allowable'=> 1,
            'next_step'     => 'Journal Entry Voucher',
            'next_due'      => $transaction_date->addDays(1),
            'last_date'     => $transaction_date,
            'status'        => 'Sign Box A of DV',
            'delay_count'   => $day_delayed,
            'calendar_days' => $cd + $result->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr_id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [journalVoucher description]
     *
     * @param  [type]            $id      [description]
     * @param  Request           $request [description]
     * @param  VoucherRepository $model   [description]
     * @return [type]                     [description]
     */
    public function journalVoucher(
        $id,
        Request $request,
        VoucherRepository $model,
        UnitPurchaseRequestRepository $upr,
        HolidayRepository $holidays)
    {
        $voucher                =   $model->findById($id);
        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('journal_entry_date') );
        $vou_date               =   Carbon::createFromFormat('!Y-m-d', $voucher->certify_date );
        $cd                     =   $vou_date->diffInDays($transaction_date);

        $day_delayed            =   $vou_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed > 1)
        {
            $day_delayed = $day_delayed - 1;
        }

        $validator = Validator::make($request->all(),[
            'journal_entry_date'    =>'required',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('jev_remarks') == null && $day_delayed > 1) {
                $validator->errors()->add('jev_remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('jev_action') == null && $day_delayed > 1) {
                $validator->errors()->add('jev_action', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Please Check Your Fields.'])
                        ->withErrors($validator)
                        ->withInput();
        }
        $inputs =   [
            'journal_entry_date'    => $request->journal_entry_date,
            'journal_entry_number'  => $request->journal_entry_number,
            'jev_remarks'           => $request->jev_remarks,
            'jev_action'           => $request->jev_action,
            'jev_days'              => $wd,
        ];

        $result =   $model->update($inputs, $id);

        $upr->update([
            'next_allowable'=> 1,
            'next_step'     => 'Sign Box D of DV',
            'next_due'      => $transaction_date->addDays(1),
            'last_date'     => $transaction_date,
            'status'        => 'Sign Box C of DV',
            'delay_count'   => $day_delayed,
            'calendar_days' => $cd + $result->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr_id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    public function counterSignVoucher($id,
        VoucherRepository $model,
        Request $request,
        UnitPurchaseRequestRepository $upr,
        HolidayRepository $holidays)
    {
        $voucher                =   $model->findById($id);
        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('counter_sign_date') );
        $vou_date               =   Carbon::createFromFormat('!Y-m-d', $voucher->payment_release_date );
        $cd                     =   $vou_date->diffInDays($transaction_date);

        $day_delayed            =   $vou_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;
        if($day_delayed > 1)
        {
            $day_delayed = $day_delayed - 1;
        }

        $validator = Validator::make($request->all(),[
            'counter_sign_date'  =>'required',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('counter_sign_remarks') == null && $day_delayed > 1) {
                $validator->errors()->add('counter_sign_remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('counter_sign_action') == null && $day_delayed > 1) {
                $validator->errors()->add('counter_sign_action', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Please Check Your Fields.'])
                        ->withErrors($validator)
                        ->withInput();
        }
        $inputs =   [
            'counter_sign_date'         => $request->counter_sign_date,
            'counter_sign_remarks'      => $request->counter_sign_remarks,
            'counter_sign_action'       => $request->counter_sign_action,
            'counter_sign_days'         => $wd,
        ];

        $result =   $model->update($inputs, $id);

        $upr->update([
            'next_allowable'=> 1,
            'next_step'     => 'Receive Payment',
            'next_due'      => $transaction_date->addDays(1),
            'last_date'     => $transaction_date,
            'status'        => 'Counter Sign Cheque',
            'delay_count'   => $day_delayed,
            'calendar_days' => $cd + $result->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr_id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [releasePayment description]
     *
     * @param  [type]            $id    [description]
     * @param  VoucherRepository $model [description]
     * @return [type]                   [description]
     */
    public function releasePayment($id,
        VoucherRepository $model,
        Request $request,
        UnitPurchaseRequestRepository $upr,
        HolidayRepository $holidays)
    {

        $voucher                =   $model->findById($id);
        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('payment_release_date') );
        $vou_date               =   Carbon::createFromFormat('!Y-m-d', $voucher->prepare_cheque_date );
        $cd                     =   $vou_date->diffInDays($transaction_date);

        $day_delayed            =   $vou_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;
        if($day_delayed > 1)
        {
            $day_delayed = $day_delayed - 1;
        }

        $validator = Validator::make($request->all(),[
            'payment_release_date'  =>'required',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('released_remarks') == null && $day_delayed > 1) {
                $validator->errors()->add('released_remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('released_action') == null && $day_delayed > 1) {
                $validator->errors()->add('released_action', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Please Check Your Fields.'])
                        ->withErrors($validator)
                        ->withInput();
        }
        $inputs =   [
            'payment_release_date'  => $request->payment_release_date,
            'payment_date'          => $request->payment_date,
            'payment_no'            => $request->payment_no,
            'bank'                  => $request->bank,
            'process_releaser'      => \Sentinel::getUser()->id,
            'released_remarks'      => $request->released_remarks,
            'released_action'       => $request->released_action,
            'released_days'         => $wd,
        ];

        $result =   $model->update($inputs, $id);

        $upr->update([
            'next_allowable'=> 1,
            'next_step'     => 'Counter Sign Cheque',
            'next_due'      => $transaction_date->addDays(1),
            'last_date'     => $transaction_date,
            'status'        => 'Sign LDDAP-ADA or Prepare Cheque',
            'delay_count'   => $day_delayed,
            'calendar_days' => $cd + $result->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr_id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [receivePayment description]
     *
     * @param  [type]            $id    [description]
     * @param  VoucherRepository $model [description]
     * @return [type]                   [description]
     */
    public function receivePayment($id,
        VoucherRepository $model,
        Request $request,
        UnitPurchaseRequestRepository $upr,
        HolidayRepository $holidays)
    {
        $voucher                =   $model->findById($id);
        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('payment_received_date') );
        $vou_date               =   Carbon::createFromFormat('!Y-m-d', $voucher->counter_sign_date );
        $cd                     =   $vou_date->diffInDays($transaction_date);

        $day_delayed            =   $vou_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed > 1)
        {
            $day_delayed = $day_delayed - 1;
        }

        $validator = Validator::make($request->all(),[
            'payment_received_date'  =>'required',
            'or'                    => 'required',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('received_remarks') == null && $day_delayed > 1) {
                $validator->errors()->add('received_remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('received_action') == null && $day_delayed > 1) {
                $validator->errors()->add('received_action', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Please Check Your Fields.'])
                        ->withErrors($validator)
                        ->withInput();
        }

        $inputs = [
            'payment_received_date' => $request->payment_received_date,
            'payment_receiver'      => \Sentinel::getUser()->id,
            'received_days'         => $wd,
            'or'                    => $request->or,
            'received_remarks'      => $request->received_remarks,
            'received_action'       => $request->received_action
        ];

        $result     =   $model->update($inputs, $id);
        $prepared_date      =   \Carbon\Carbon::createFromFormat('!Y-m-d', $result->upr->date_processed->format('Y-m-d'));
        $completed_date     =   \Carbon\Carbon::createFromFormat('Y-m-d', $request->payment_received_date);

        $days               =   $completed_date->diffInDays($prepared_date);

        $upr->update([
            'next_allowable'=> 0,
            'next_step'     => 'Complete',
            'next_due'      => $transaction_date,
            'last_date'     => $transaction_date,
            'status'        => 'completed',
            'state'         => 'completed',
            'completed_at'  => $request->payment_received_date,
            'delay_count'   => $cd + $result->upr->delay_count,
            'days'          => $days],
        $result->upr_id);

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
    public function destroy($id, VoucherRepository $model)
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
    public function viewLogs($id, VoucherRepository $model, AuditLogRepository $logs)
    {
        $modelType  =   'Revlv\Procurements\Vouchers\VoucherEloquent';
        $result     =   $logs->findByModelAndId($modelType, $id);
        $data_model =   $model->findById($id);

        return $this->view('modules.procurements.vouchers.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'model'         =>  $data_model,
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('Voucher', 'procurements.vouchers.show',$data_model->id),
                new Breadcrumb('Logs'),
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
    public function updateSignatory(Request $request, $id, VoucherRepository $model)
    {
        $this->validate($request, [
            'certified_by'      =>  'required',
            'approver_id'       =>  'required',
            'receiver_id'       =>  'required',
        ]);

        $data   =   [
            'certified_by'      =>  $request->certified_by,
            'approver_id'       =>  $request->approver_id,
            'receiver_id'       =>  $request->receiver_id,
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
    public function viewPrint($id, VoucherRepository $model, NOARepository $noa, UnitPurchaseRequestRepository $upr, HeaderRepository $headers)
    {
        $result     =   $model->with(['receiver', 'approver', 'certifier'])->findById($id);
        $noa_model  =   $noa->with(['winner','upr'])->findByUPR($result->upr_id);

        if($noa_model->upr->mode_of_procurement != 'public_bidding')
        {
            $winner     =   $noa_model->winner->supplier;
        }
        else
        {
            $winner     =   $noa_model->biddingWinner->supplier;
        }
        $certifier                     =   explode('/', $result->certified_signatory);
        $approver                      =   explode('/', $result->approver_signatory);
        $receiver                      =   explode('/', $result->receiver_signatory);
        $header                     =  $headers->findByUnit($result->upr->units);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $data['transaction_date']       =   $result->transaction_date;
        $data['bir_address']            =   $result->bir_address;
        $data['suppliers_address']            =   $result->suppliers_address;
        $data['final_tax']              =   $result->final_tax;
        $data['receiver']               =   $receiver;
        $data['or']                     =   $result->or;
        $data['approver']               =   $approver;
        $data['certifier']              =   $certifier;
        $data['expanded_witholding_tax']=   $result->expanded_witholding_tax;
        $data['ewt_amount']             =   $result->ewt_amount;
        $data['final_tax_amount']       =   $result->final_tax_amount;
        $data['amount']                 =   $result->amount;
        $data['payment_no']             =   $result->payment_no;
        $data['payment_date']           =   $result->payment_date;
        $data['payment_bank']           =   ($result->banks) ? $result->banks->code : "";
        $data['payee']                  =   $winner;
        $data['upr']                    =   $noa_model->upr;
        $data['po']                     =   $noa_model->upr->purchase_order;
        $data['delivery_terms']         =   $noa_model->upr->purchase_order->delivery_terms;
        $data['delivery_date']          =   $noa_model->upr->delivery_order->delivery_date;
        $data['ntp_date']               =   $noa_model->upr->ntp->award_accepted_date;
        $data['header']                 =   $result->upr->centers;
        $data['bid_amount']             =   $result->upr->purchase_order->bid_amount;
        $data['items']                  =   $result->upr->items;
        $data['po_type']                =   $result->upr->purchase_order->type;
        $data['po_number']              =   $result->upr->purchase_order->po_number;
        $ntp_date                       =   Carbon::createFromFormat('!Y-m-d',$data['ntp_date']);
        $delivery_date                  =   Carbon::createFromFormat('!Y-m-d',$data['delivery_date']);

        $penalty_amount = 0;
        $po_term        =   $result->upr->purchase_order->delivery_terms;

        foreach($result->upr->delivery_orders as $dr)
        {
            if($ntp_date < $dr->delivery_date)
            {
                $delivery_date                  =   Carbon::createFromFormat('!Y-m-d', $dr->delivery_date);
                $nr_delay                       =   $ntp_date->diffInDays($delivery_date, false);
                if($nr_delay > $po_term)
                {
                    $penalty_day    =   $nr_delay  - $po_term;
                    foreach($dr->items as $item)
                    {
                        $amount =   $item->total_amount;
                        $penalty_amount += (($amount * 0.01) * 0.1) * $penalty_day;
                    }
                }
                // $penalty                        =   (($result->amount * 0.01) * 0.1) * $nr_delay;
            }
        }

        // $nr_delay                       =   $ntp_date->diffInDays($delivery_date, false);
        // $penalty                        =   (($result->amount * 0.01) * 0.1) * $nr_delay;
        // $data['nr_delay']               =   $nr_delay;

        $data['penalty']                =   $penalty_amount;
        $pdf = PDF::loadView('forms.voucher', ['data' => $data])
            ->setOption('margin-bottom', 30)
            ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('voucher.pdf');
    }

    /**
     * [viewPrint description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrint2($id,
      VoucherRepository $model,
      NOARepository $noa,
      UnitPurchaseRequestRepository $upr,
      VoucherForm $voucherForm,
      HeaderRepository $headers)
    {
        $result     =   $model->with(['receiver', 'approver', 'certifier'])->findById($id);
        $noa_model  =   $noa->with(['winner','upr'])->findByUPR($result->upr_id);

        if($noa_model->upr->mode_of_procurement != 'public_bidding')
        {
            $winner     =   $noa_model->winner->supplier;
        }
        else
        {
            $winner     =   $noa_model->biddingWinner->supplier;
        }
        $certifier                     =   explode('/', $result->certified_signatory);
        $approver                      =   explode('/', $result->approver_signatory);
        $receiver                      =   explode('/', $result->receiver_signatory);
        $header                     =  $headers->findByUnit($result->upr->units);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $data['transaction_date']       =   $result->transaction_date;
        $data['bir_address']            =   $result->bir_address;
        $data['suppliers_address']            =   $result->suppliers_address;
        $data['final_tax']              =   $result->final_tax;
        $data['receiver']               =   $receiver;
        $data['or']                     =   $result->or;
        $data['approver']               =   $approver;
        $data['certifier']              =   $certifier;
        $data['expanded_witholding_tax']=   $result->expanded_witholding_tax;
        $data['ewt_amount']             =   $result->ewt_amount;
        $data['final_tax_amount']       =   $result->final_tax_amount;
        $data['amount']                 =   $result->amount;
        $data['payment_no']             =   $result->payment_no;
        $data['payment_date']           =   $result->payment_date;
        $data['payment_bank']           =   ($result->banks) ? $result->banks->code : "";
        $data['payee']                  =   $winner;
        $data['upr']                    =   $noa_model->upr;
        $data['po']                     =   $noa_model->upr->purchase_order;
        $data['delivery_terms']         =   $noa_model->upr->purchase_order->delivery_terms;
        $data['delivery_date']          =   $noa_model->upr->delivery_order->delivery_date;
        $data['ntp_date']               =   $noa_model->upr->ntp->award_accepted_date;
        $data['header']                 =   $result->upr->centers;
        $data['bid_amount']             =   $result->upr->purchase_order->bid_amount;
        $data['items']                  =   $result->upr->items;
        $data['po_type']                =   $result->upr->purchase_order->type;
        $ntp_date                       =   Carbon::createFromFormat('!Y-m-d',$data['ntp_date']);
        $delivery_date                  =   Carbon::createFromFormat('!Y-m-d',$data['delivery_date']);

        $penalty_amount = 0;
        $po_term        =   $result->upr->purchase_order->delivery_terms;

        foreach($result->upr->delivery_orders as $dr)
        {
            if($ntp_date < $dr->delivery_date)
            {
                $delivery_date                  =   Carbon::createFromFormat('!Y-m-d', $dr->delivery_date);
                $nr_delay                       =   $ntp_date->diffInDays($delivery_date, false);
                if($nr_delay > $po_term)
                {
                    $penalty_day    =   $nr_delay  - $po_term;
                    foreach($dr->items as $item)
                    {
                        $amount =   $item->total_amount;
                        $penalty_amount += (($amount * 0.01) * 0.1) * $penalty_day;
                    }
                }
                // $penalty                        =   (($result->amount * 0.01) * 0.1) * $nr_delay;
            }
        }

        // $nr_delay                       =   $ntp_date->diffInDays($delivery_date, false);
        // $penalty                        =   (($result->amount * 0.01) * 0.1) * $nr_delay;
        // $data['nr_delay']               =   $nr_delay;
        switch ($data['po_type']) {
          case 'purchase_order':
            $data['poType']  = "PURCHASE ORDER No.";
            break;
          case 'work_order':
            $data['poType']  = "WORK ORDER No.";
            break;
          case 'job_order':
            $data['poType']  = "JOB ORDER No.";
            break;
          default:
            $data['poType']  = "CONTRACT ORDER No.";
            # code...
            break;
        }
        $data['penalty']                =   $penalty_amount;

        $form       =   $voucherForm->findByUnit($result->upr->units);
        $contents   =   "";
        if($form != null) {

          $contents   =   $form->content;
        }
        else
        {
          $file_path = base_path()."/resources/views/forms/default-voucher.blade.php";
          if(file_exists($file_path))
          {
            $contents = \File::get($file_path);
          }
        }

        $output = preg_replace_callback('~\{{(.*?)\}}~', function($key)use($data, $receiver, $approver, $certifier, $result) {
            $variable['bid_amount_word']      = translateToWords(str_replace(',', '', $data['bid_amount']));
            $variable['receiver_name']        = (count($receiver) > 1) ? $receiver[0] : "";
            $variable['receiver_ranks']       = (count($receiver) > 1) ? $receiver[1] : "";
            $variable['receiver_branch']      = (count($receiver) > 1) ? $receiver[2] : "";
            $variable['receiver_designation'] = (count($receiver) > 1) ? $receiver[3] : "";
            $variable['approver_name']        = (count($approver) > 1) ? $approver[0] : "";
            $variable['approver_ranks']       = (count($approver) > 1) ? $approver[1] : "";
            $variable['approver_branch']      = (count($approver) > 1) ? $approver[2] : "";
            $variable['approver_designation'] = (count($approver) > 1) ? $approver[3] : "";
            $variable['certifier_name']       = (count($certifier) > 1) ? $certifier[0] : "";
            $variable['certifier_ranks']      = (count($certifier) > 1) ? $certifier[1] : "";
            $variable['certifier_branch']     = (count($certifier) > 1) ? $certifier[2] : "";
            $variable['certifier_designation']= (count($certifier) > 1) ? $certifier[3] : "";
            $variable['po_number']         = $result->upr->purchase_order->po_number;
            $variable['poType']            = $data['poType'];
            $variable['itemCount']         = count($data['items']);
            $variable['bid_amount']        = formatPrice($data['bid_amount']);
            $variable['final_tax']         = $data['final_tax'];
            $variable['expanded_witholding_tax']= $data['expanded_witholding_tax'];
            $variable['final_tax_amount']  = formatPrice($data['final_tax_amount']);
            $variable['ewt_amount']        = formatPrice($data['ewt_amount']);
            $variable['penalty']           = formatPrice($data['penalty']);
            $variable['unitHeader']        = $data['unitHeader'];
            $variable['payee_tin']     = $data['payee']->tin;
            $variable['payee_name']    = $data['payee']->name;
            $variable['payee_address'] = $data['payee']->address;
            if(isset($variable[$key[1]]) ){
              return $variable[$key[1]];
            }
            return $key[1];
        },
        $contents);

        $pdf = PDF::loadView('forms.voucher-form-2', ['content' => $output, 'data' => $data])
            ->setOption('margin-bottom', 30)
            ->setOption('footer-html', route('pdf.footer'));
        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('voucher.pdf');

        $pdf = PDF::loadView('forms.voucher-form-2', ['data' => $data])
            ->setOption('margin-bottom', 30)
            ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('voucher.pdf');
    }

    /**
     * [viewPrint description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrintNoTax($id, VoucherRepository $model, NOARepository $noa, UnitPurchaseRequestRepository $upr, HeaderRepository $headers)
    {
        $result     =   $model->with(['receiver', 'approver', 'certifier'])->findById($id);
        $noa_model  =   $noa->with(['winner','upr'])->findByUPR($result->upr_id);

        if($noa_model->upr->mode_of_procurement != 'public_bidding')
        {
            $winner     =   $noa_model->winner->supplier;
        }
        else
        {
            $winner     =   $noa_model->biddingWinner->supplier;
        }


        $certifier                     =   explode('/', $result->certified_signatory);
        $approver                      =   explode('/', $result->approver_signatory);
        $receiver                      =   explode('/', $result->receiver_signatory);
        $header                     =  $headers->findByUnit($result->upr->units);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $data['transaction_date']       =   $result->transaction_date;
        $data['bir_address']            =   $result->bir_address;
        $data['suppliers_address']            =   $result->suppliers_address;
        $data['final_tax']              =   $result->final_tax;
        $data['receiver']               =   $receiver;
        $data['or']                     =   $result->or;
        $data['approver']               =   $approver;
        $data['certifier']              =   $certifier;
        $data['amount']                 =   $result->amount;
        $data['payee']                  =   $winner;
        $data['upr']                    =   $noa_model->upr;
        $data['po']                     =   $noa_model->upr->purchase_order;
        $data['delivery_terms']         =   $noa_model->upr->purchase_order->delivery_terms;
        $data['delivery_date']          =   $noa_model->upr->delivery_order->delivery_date;
        $data['ntp_date']               =   $noa_model->upr->ntp->award_accepted_date;
        $data['header']                 =   $result->upr->centers;
        $data['bid_amount']             =   $result->upr->purchase_order->bid_amount;
        $data['items']                  =   $result->upr->items;
        $data['po_type']                =   $result->upr->purchase_order->type;
        $data['po_number']              =   $result->upr->purchase_order->po_number;

        $ntp_date                       =   Carbon::createFromFormat('!Y-m-d',$data['ntp_date']);
        $delivery_date                  =   Carbon::createFromFormat('!Y-m-d',$data['delivery_date']);

        $pdf = PDF::loadView('forms.voucher-no-tax', ['data' => $data])
            ->setOption('margin-bottom', 30)
            ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('voucher.pdf');
    }
}
