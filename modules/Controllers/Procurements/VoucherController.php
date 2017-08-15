<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use Carbon\Carbon;
use Validator;
use \App\Support\Breadcrumb;


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
    protected $noa;
    protected $rfq;
    protected $audits;
    protected $signatories;
    protected $banks;
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
    public function getDatatable(VoucherRepository $model)
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
        return $this->view('modules.procurements.vouchers.index',[
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
        $diir_date              =   Carbon::createFromFormat('Y-m-d', $rfq_model->diir->closed_date );
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
            'amount'       => 'required',
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

        $upr->update([
            'next_allowable'=> 1,
            'next_step'     => 'Pre Audit Voucher',
            'next_due'      => $transaction_date->addDays(1),
            'last_date'     => $transaction_date,
            'status'        => 'Voucher Created',
            'delay_count'   => $day_delayed,
            'calendar_days' => $cd + $result->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr_id);

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

        return $this->view('modules.procurements.vouchers.show',[
            'data'              =>  $result,
            'signatory_list'    =>  $signatory_lists,
            'bank_list'         =>  $bank_list  ,
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
    public function edit($id, VoucherRepository $model)
    {
        $result     =   $model->findById($id);

        return $this->view('modules.procurements.vouchers.edit',[
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
        VoucherRepository $model)
    {
        $this->validate($request, [
            'update_remarks'        =>  'required',
            'transaction_date'      =>  'required',
            // 'payment_release_date'  =>  'required',
            // 'payment_received_date' =>  'required',
            // 'preaudit_date'         =>  'required',
            // 'certify_date'          =>  'required',
            // 'journal_entry_date'    =>  'required',
            // 'approval_date'         =>  'required'
        ]);

        $data   =   [
            'update_remarks'        =>  $request->update_remarks,
            'transaction_date'      =>  $request->transaction_date,
            'payment_release_date'  =>  $request->payment_release_date,
            'payment_received_date' =>  $request->payment_received_date,
            'preaudit_date'         =>  $request->preaudit_date,
            'certify_date'          =>  $request->certify_date,
            'journal_entry_date'    =>  $request->journal_entry_date,
            'approval_date'         =>  $request->approval_date
        ];

        $result                 =   $model->update($data, $id);

        $rfq_model              =   $result->upr;

        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('transaction_date') );
        $diir_date              =   Carbon::createFromFormat('Y-m-d', $rfq_model->diir->closed_date );

        $day_delayed            =   $diir_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed != 0)
        {
            $day_delayed = $day_delayed - 1;
        }

        if($day_delayed != $result->days)
        {
            $model->update(['days' => $day_delayed], $id);
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
        $diir_date              =   Carbon::createFromFormat('Y-m-d', $voucher->transaction_date );
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
            'preaudit_date'       => 'required',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('preaudit_remarks') == null && $day_delayed > 1) {
                $validator->errors()->add('preaudit_remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('preaudit_action') == null && $day_delayed > 1) {
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

        // $upr->update([
        //     'next_allowable'=> 1,
        //     'next_step'     => 'Certify Voucher',
        //     'next_due'      => $transaction_date->addDays(1),
        //     'last_date'     => $transaction_date,
        //     'status'        => 'Voucher Preaudit',
        //     'delay_count'   => $day_delayed,
        //     'calendar_days' => $cd + $result->upr->calendar_days,
        //     'last_action'   => $request->action,
        //     'last_remarks'  => $request->remarks
        //     ], $result->upr_id);

        $upr->update([
            'next_allowable'=> 0,
            'next_step'     => 'Complete',
            'next_due'      => $transaction_date,
            'last_date'     => $transaction_date,
            'status'        => 'completed',
            'state'         => 'completed',
            'completed_at'  => $request->preaudit_date,
            'delay_count'   => $cd + $result->upr->delay_count,
            'days'          => $wd],
       $result->upr_id);


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
        $diir_date              =   Carbon::createFromFormat('Y-m-d', $voucher->journal_entry_date );
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

        // $upr->update([
        //     'next_allowable'=> 1,
        //     'next_step'     => 'Release Voucher',
        //     'next_due'      => $transaction_date->addDays(1),
        //     'last_date'     => $transaction_date,
        //     'status'        => 'Voucher Approved',
        //     'delay_count'   => $day_delayed,
        //     'calendar_days' => $cd + $result->upr->calendar_days,
        //     'last_action'   => $request->action,
        //     'last_remarks'  => $request->remarks
        //     ], $result->upr_id);


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
        $vou_date               =   Carbon::createFromFormat('Y-m-d', $voucher->preaudit_date );
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
            'certify_action'                   => $request->certify_action,
            'certify_days'                              => $wd,
        ];

        $result =   $model->update($inputs, $id);

        // $upr->update([
        //     'next_allowable'=> 1,
        //     'next_step'     => 'Voucher JEV',
        //     'next_due'      => $transaction_date->addDays(1),
        //     'last_date'     => $transaction_date,
        //     'status'        => 'Voucher Certify',
        //     'delay_count'   => $day_delayed,
        //     'calendar_days' => $cd + $result->upr->calendar_days,
        //     'last_action'   => $request->action,
        //     'last_remarks'  => $request->remarks
        //     ], $result->upr_id);

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
        $vou_date               =   Carbon::createFromFormat('Y-m-d', $voucher->certify_date );
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
            // 'journal_entry_number'  =>'required',
            // 'or'                    =>'required',
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
            'or'                    => $request->or,
            'jev_remarks'           => $request->jev_remarks,
            'jev_action'           => $request->jev_action,
            'jev_days'              => $wd,
        ];

        $result =   $model->update($inputs, $id);

        // $upr->update([
        //     'next_allowable'=> 1,
        //     'next_step'     => 'Approve Voucher',
        //     'next_due'      => $transaction_date->addDays(1),
        //     'last_date'     => $transaction_date,
        //     'status'        => 'Voucher Journal Entry',
        //     'delay_count'   => $day_delayed,
        //     'calendar_days' => $cd + $result->upr->calendar_days,
        //     'last_action'   => $request->action,
        //     'last_remarks'  => $request->remarks
        //     ], $result->upr_id);

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
        $vou_date               =   Carbon::createFromFormat('Y-m-d', $voucher->approval_date );
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


       //  $upr->update([
       //      'next_allowable'=> 0,
       //      'next_step'     => 'Complete',
       //      'next_due'      => $transaction_date,
       //      'last_date'     => $transaction_date,
       //      'status'        => 'completed',
       //      'state'         => 'completed',
       //      'completed_at'  => $request->payment_release_date,
       //      'delay_count'   => $cd + $result->upr->delay_count,
       //      'days'          => $wd],
       // $result->upr_id);

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
        $vou_date               =   Carbon::createFromFormat('Y-m-d', $voucher->payment_release_date );
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
            'received_remarks'      => $request->received_remarks,
            'received_action'      => $request->received_action
        ];

        $result     =   $model->update($inputs, $id);

        $prepared_date      =   \Carbon\Carbon::createFromFormat('Y-m-d', $result->upr->date_prepared->format('Y-m-d'));
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
    public function viewPrint($id, VoucherRepository $model, NOARepository $noa, UnitPurchaseRequestRepository $upr)
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

        $data['transaction_date']       =   $result->transaction_date;
        $data['bir_address']            =   $result->bir_address;
        $data['final_tax']              =   $result->final_tax;
        $data['receiver']               =   $result->receiver;
        $data['or']                     =   $result->or;
        $data['approver']               =   $result->approver;
        $data['certifier']              =   $result->certifier;
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

        $ntp_date                       =   Carbon::createFromFormat('Y-m-d',$data['ntp_date']);
        $delivery_date                  =   Carbon::createFromFormat('Y-m-d',$data['delivery_date']);
        $nr_delay                       =   $ntp_date->diffInDays($delivery_date, false);
        $penalty                        =   (($result->amount * 0.01) * 0.1) * $nr_delay;

        $data['nr_delay']               =   $nr_delay;
        $data['penalty']                =   $penalty;
        $pdf = PDF::loadView('forms.voucher', ['data' => $data])
            ->setOption('margin-bottom', 30)
            ->setOption('footer-html', route('pdf.footer'))
            ->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('voucher.pdf');
    }

    /**
     * [viewPrint description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrintNoTax($id, VoucherRepository $model, NOARepository $noa, UnitPurchaseRequestRepository $upr)
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

        $data['transaction_date']       =   $result->transaction_date;
        $data['bir_address']            =   $result->bir_address;
        $data['final_tax']              =   $result->final_tax;
        $data['receiver']               =   $result->receiver;
        $data['or']                     =   $result->or;
        $data['approver']               =   $result->approver;
        $data['certifier']              =   $result->certifier;
        $data['amount']                 =   $result->amount;
        $data['payee']                  =   $winner;
        $data['upr']                    =   $noa_model->upr;
        $data['po']                     =   $noa_model->upr->purchase_order;
        $data['delivery_terms']         =   $noa_model->upr->purchase_order->delivery_terms;
        $data['delivery_date']          =   $noa_model->upr->delivery_order->delivery_date;
        $data['ntp_date']               =   $noa_model->upr->ntp->award_accepted_date;

        $ntp_date                       =   Carbon::createFromFormat('Y-m-d',$data['ntp_date']);
        $delivery_date                  =   Carbon::createFromFormat('Y-m-d',$data['delivery_date']);

        $pdf = PDF::loadView('forms.voucher-no-tax', ['data' => $data])
            ->setOption('margin-bottom', 30)
            ->setOption('footer-html', route('pdf.footer'))
            ->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('voucher.pdf');
    }
}
