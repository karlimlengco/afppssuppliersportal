<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;

use \Revlv\Procurements\Vouchers\VoucherRepository;
use \Revlv\Procurements\Vouchers\VoucherRequest;
use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\NoticeOfAward\NOARepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\Banks\BankRepository;

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
            'createRoute'   =>  $this->baseUrl."create"
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
    public function store(VoucherRequest $request, VoucherRepository $model, BlankRFQRepository $rfq, UnitPurchaseRequestRepository $upr)
    {
        $rfq_model                  =   $rfq->findById($request->rfq_id);
        $inputs                     =   $request->getData();
        $inputs['rfq_number']       =   $rfq_model->rfq_number;
        $inputs['transaction_date'] =   $request->voucher_transaction_date;
        $inputs['upr_number']       =   $rfq_model->upr_number;
        $inputs['upr_id']           =   $rfq_model->upr_id;
        $inputs['prepared_by']      =   \Sentinel::getUser()->id;
        $result = $model->save($inputs);
        $upr->update(['status' => 'Voucher Created'], $result->upr_id);

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
                    'method'    =>  'PUT'
                ],
                'destroy'   => [
                    'route' => [$this->baseUrl.'destroy',$id],
                    'method'=> 'DELETE'
                ]
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
    public function update(Request $request, $id, VoucherRepository $model)
    {
        $this->validate($request, [
            'update_remarks'        =>  'required',
            'transaction_date'      =>  'required',
            'payment_release_date'  =>  'required',
            'payment_received_date' =>  'required',
            'preaudit_date'         =>  'required',
            'certify_date'          =>  'required',
            'journal_entry_date'    =>  'required',
            'approval_date'         =>  'required'
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

        $model->update($data, $id);

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
    public function preauditVoucher($id, Request $request, VoucherRepository $model)
    {
        $this->validate($request, ['preaudit_date'=>'required']);

        $model->update(['preaudit_date' => $request->preaudit_date], $id);

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
    public function approvedVoucher($id, Request $request, VoucherRepository $model)
    {
        $this->validate($request, ['approval_date'=>'required']);

        $model->update(['approval_date' => $request->approval_date], $id);

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
    public function certifyVoucher($id, Request $request, VoucherRepository $model)
    {
        $this->validate($request, [
            'certify_date'                      =>'required',
            'is_cash_avail'                     =>'required',
            'subject_to_authority_to_debit_acc' =>'required',
            'documents_completed'               =>'required',
        ]);

        $model->update([
            'certify_date'                      => $request->certify_date,
            'is_cash_avail'                     => $request->is_cash_avail,
            'subject_to_authority_to_debit_acc' => $request->subject_to_authority_to_debit_acc,
            'documents_completed'               => $request->documents_completed
        ], $id);

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
    public function journalVoucher($id, Request $request, VoucherRepository $model)
    {
        $this->validate($request, [
            'journal_entry_date'    =>'required',
            'journal_entry_number'  =>'required',
            'or'                    =>'required',
        ]);

        $model->update([
            'journal_entry_date'    => $request->journal_entry_date,
            'journal_entry_number'  => $request->journal_entry_number,
            'or'                    => $request->or,
        ], $id);

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
    public function releasePayment($id, VoucherRepository $model, Request $request)
    {
        $result =   $model->update([
            'payment_release_date'  => $request->payment_release_date,
            'payment_date'          => $request->payment_date,
            'payment_no'            => $request->payment_no,
            'bank'                  => $request->bank,
            'process_releaser'      => \Sentinel::getUser()->id,
        ], $id);

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
    public function receivePayment($id, VoucherRepository $model, Request $request, UnitPurchaseRequestRepository $upr)
    {
        $result     =   $model->update([
            'payment_received_date' => $request->payment_received_date,
            'payment_receiver'      => \Sentinel::getUser()->id,
        ], $id);

        $prepared_date      =   \Carbon\Carbon::createFromFormat('Y-m-d', $result->upr->date_prepared);
        $completed_date     =   \Carbon\Carbon::createFromFormat('Y-m-d', $request->payment_received_date);

        $days               =   $completed_date->diffInDays($prepared_date);

        $upr->update(['status' => 'completed', 'state' => 'completed', 'completed_at' => $request->payment_received_date, 'days' => $days], $result->upr_id);

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
            'model'         =>  $data_model
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
        $noa_model  =   $noa->with(['winner','upr'])->findByRFQ($result->rfq_id);
        $winner     =   $noa_model->winner->supplier;

        $data['transaction_date']       =   $result->transaction_date;
        $data['bir_address']            =   $result->bir_address;
        $data['final_tax']              =   $result->final_tax;
        $data['receiver']               =   $result->receiver;
        $data['or']                     =   $result->or;
        $data['approver']               =   $result->approver;
        $data['certifier']              =   $result->certifier;
        $data['journal_entry_date']     =   $result->journal_entry_date;
        $data['journal_entry_number']   =   $result->journal_entry_number;
        $data['payment_received_date']  =   $result->payment_received_date;
        $data['approval_date']          =   $result->approval_date;
        $data['certify_date']           =   $result->certify_date;
        $data['expanded_witholding_tax']=   $result->expanded_witholding_tax;
        $data['ewt_amount']             =   $result->ewt_amount;
        $data['final_tax_amount']       =   $result->final_tax_amount;
        $data['payment_no']             =   $result->payment_no;
        $data['payment_date']           =   $result->payment_date;
        $data['payment_bank']           =   $result->banks->code;
        $data['payee']                  =   $winner;
        $data['upr']                    =   $noa_model->upr;
        $data['po']                     =   $noa_model->upr->purchase_order;

        $pdf = PDF::loadView('forms.voucher', ['data' => $data])->setOption('margin-bottom', 0)->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('voucher.pdf');
    }
}
