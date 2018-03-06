<?php

namespace Revlv\Controllers\Biddings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use PDF;
use Carbon\Carbon;
use Validator;
use \App\Support\Breadcrumb;
use App\Events\Event;

use \Revlv\Procurements\PurchaseOrder\PORepository;
use \Revlv\Procurements\NoticeOfAward\NOARepository;
use \Revlv\Procurements\PurchaseOrder\Items\ItemRepository;
use \Revlv\Procurements\PurchaseOrder\PORequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\RFQProponents\RFQProponentRepository;
use \Revlv\Settings\PaymentTerms\PaymentTermRepository;
use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\Holidays\HolidayRepository;
use \Revlv\Users\Logs\UserLogRepository;
use \Revlv\Users\UserRepository;

class PurchaseOrderController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "biddings.purchase-orders.";

    /**
     * [$blank description]
     *
     * @var [type]
     */
    protected $blank;
    protected $items;
    protected $upr;
    protected $noa;
    protected $rfq;
    protected $terms;
    protected $proponents;
    protected $signatories;
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
    public function getDatatable(PORepository $model)
    {
        return $model->getDatatable('bidding');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.biddings.purchase-order.index',[
            'createRoute'   =>  $this->baseUrl."create",
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb('Purchase Order', 'biddings.purchase-orders.index'),
            ]
        ]);
    }



    /**
     * [coaApproved description]
     *
     * @param  [type]       $id      [description]
     * @param  Request      $request [description]
     * @param  PORepository $model   [description]
     * @return [type]                [description]
     */
    public function coaApproved(
        $id,
        Request $request,
        UnitPurchaseRequestRepository $upr,
        PORepository $model,
        HolidayRepository $holidays)
    {
        $po                     =   $model->findById($id);
        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('coa_approved_date') );
        $po_date                =   Carbon::createFromFormat('!Y-m-d', $po->mfo_received_date );

        // Delay
        $day_delayed            =   $po_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $validator = Validator::make($request->all(),[
            'file'              => 'required',
            'coa_approved_date' => 'required',
            'coa_action'        => 'required_with:coa_remarks',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('coa_remarks') == null && $day_delayed > 1) {
                $validator->errors()->add('coa_remarks', 'This field is required when your process is delay');
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

        $file       = md5_file($request->file);
        $file       = $file.".".$request->file->getClientOriginalExtension();

        $data       =   [
            'coa_file'          =>  $file,
            'coa_approved_date' =>  $request->coa_approved_date,
            'coa_approved'      =>  \Sentinel::getUser()->id,
            'status'            =>  'COA Approved',
            'coa_remarks'       => $request->coa_remarks,
            'coa_action'       => $request->coa_action,
            'coa_days'          => $day_delayed,
        ];

        $result =   $model->update($data, $id);

        if($result)
        {
            $path       = $request->file->storeAs('coa-approved-attachments', $file);
        }

        $upr_result  = $upr->update([
            'status' => 'PO Approved',
            'delay_count'   => ($day_delayed > 1 )? $day_delayed - 1 : 0,
            'calendar_days' => $day_delayed + $po->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr_id);

        event(new Event($upr_result, $upr_result->ref_number." PO Approved"));

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Purchase Order has been successfully approved."
        ]);
    }

    /**
     * [mfoApproved description]
     *
     * @param  [type]       $id      [description]
     * @param  Request      $request [description]
     * @param  PORepository $model   [description]
     * @return [type]                [description]
     */
    public function mfoApproved($id,
        Request $request,
        PORepository $model,
        UnitPurchaseRequestRepository $upr,
        HolidayRepository $holidays)
    {
        $po                     =   $model->findById($id);
        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('mfo_received_date') );
        $po_date                =   Carbon::createFromFormat('!Y-m-d', $po->funding_received_date );

        // Delay
        $day_delayed            =   $po_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $validator = Validator::make($request->all(),[
            'mfo_released_date' =>  'required',
            'mfo_received_date' =>  'required',
            'mfo_action'        =>  'required_with:mfo_remarks',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('mfo_remarks') == null && $day_delayed > 2) {
                $validator->errors()->add('mfo_remarks', 'This field is required when your process is delay');
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

        $inputs =   [
            'mfo_released_date' => $request->mfo_released_date,
            'mfo_received_date' => $request->mfo_received_date,
            'mfo_remarks'       => $request->mfo_remarks,
            'mfo_action'       => $request->mfo_action,
            'mfo_days'          => $day_delayed,
        ];

        $result =   $model->update($inputs, $id);

        $model->update(['status' => 'MFO Approved'], $id);

        $upr_result  = $upr->update([
            'next_allowable'=> 1,
            'next_step'     => 'COA Approval',
            'next_due'      => $transaction_date->addDays(1),
            'last_date'     => $transaction_date,
            'status'        => "PO MFO Approved",
            'delay_count'   => ($day_delayed > 2 )? $day_delayed - 2 : 0,
            'calendar_days' => $cd + $po->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $po->upr_id);


        event(new Event($upr_result, $upr_result->ref_number." MFO Approved"));


        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);

    }

    /**
     * [pccoApproved description]
     *
     * @param  [type]       $id      [description]
     * @param  Request      $request [description]
     * @param  PORepository $model   [description]
     * @return [type]                [description]
     */
    public function pccoApproved($id,
        Request $request,
        PORepository $model,
        UnitPurchaseRequestRepository $upr,
        HolidayRepository $holidays)
    {
        $po                     =   $model->findById($id);
        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('funding_received_date') );
        $po_date                =   Carbon::createFromFormat('!Y-m-d', $po->purchase_date );

        // Delay
        $day_delayed            =   $po_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $validator = Validator::make($request->all(),[
            'funding_released_date' =>  'required',
            'funding_received_date' =>  'required',
            'funding_action' =>  'required_with:funding_remarks',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('funding_remarks') == null && $day_delayed > 2) {
                $validator->errors()->add('funding_remarks', 'This field is required when your process is delay');
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
        $inputs =   [
            'funding_released_date' => $request->funding_released_date,
            'funding_received_date' => $request->funding_received_date,
            'funding_remarks'       => $request->funding_remarks,
            'funding_action'       => $request->funding_action,
            'funding_days'          => $day_delayed
        ];

        $result =   $model->update($inputs, $id);

        $model->update(['status' => 'Accounting Approved'], $id);

        $upr_result  = $upr->update([
            'status' => "PO Funding Approved",
            'delay_count'   => ($day_delayed > 2 )? $day_delayed - 2 : 0,
            'calendar_days' => $day_delayed + $po->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $po->upr_id);

        event(new Event($upr_result, $upr_result->ref_number." Funding Approved"));

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);

    }

    public function createFromRfq(
        $id,
        BlankRFQRepository $rfq,
        PaymentTermRepository $terms,
        PORepository $model)
    {

        $term_lists =   $terms->lists('id','name');

        $this->view('modules.procurements.purchase-order.create-from-rfq',[
            'indexRoute'    =>  $this->baseUrl.'index',
            'term_lists'    =>  $term_lists,
            'rfq_id'        =>  $id,
            'modelConfig'   =>  [
                'store' =>  [
                    'route'     =>  [$this->baseUrl.'store-from-rfq',$id]
                ]
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(BlankRFQRepository $rfq, PaymentTermRepository $terms)
    {
        $rfq_list   =   $rfq->listsAccepted('id', 'rfq_number');

        $term_lists =   $terms->lists('id','name');

        $this->view('modules.procurements.purchase-order.create',[
            'indexRoute'    =>  $this->baseUrl.'index',
            'rfq_list'      =>  $rfq_list,
            'term_lists'    =>  $term_lists,
            'modelConfig'   =>  [
                'store' =>  [
                    'route'     =>  $this->baseUrl.'store'
                ]
            ]
        ]);
    }

    /**
     * [storeFromRfq description]
     *
     * @param  [type]                        $id      [description]
     * @param  Request                       $request [description]
     * @param  PORepository                  $model   [description]
     * @param  ItemRepository                $items   [description]
     * @param  NOARepository                 $noa     [description]
     * @param  UnitPurchaseRequestRepository $upr     [description]
     * @param  BlankRFQRepository            $rfq     [description]
     * @return [type]                                 [description]
     */
    public function storeFromRfq(
        $id,
        Request $request,
        PORepository $model,
        ItemRepository $items,
        NOARepository $noa,
        UnitPurchaseRequestRepository $upr,
        BlankRFQRepository $rfq,
        HolidayRepository $holidays)
    {
        $noa_model              =   $noa->with('winner')->findByUPR($id);
        // dd($noa_model);
        $award_accepted_date    =   Carbon::createFromFormat('!Y-m-d', $noa_model->award_accepted_date);
        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('purchase_date') );


        // Delay
        $day_delayed            =   $award_accepted_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $validator = Validator::make($request->all(),[
            'purchase_date'     => 'required',
            'payment_term'      => 'required',
            'delivery_terms'    => 'required|integer',
            'action'            => 'required_with:remarks',
            'unit_price.*'      => 'required',
            'type.*'            => 'required',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('remarks') == null && $day_delayed > 2) {
                $validator->errors()->add('remarks', 'This field is required when your process is delay');
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

        $inputs                 =   [
            'purchase_date' =>  $request->purchase_date,
            'payment_term'  =>  $request->payment_term,
            'delivery_terms'=>  $request->delivery_terms,
            'action'       =>  $request->action,
            'remarks'       =>  $request->remarks,
            'days'          =>  $day_delayed
        ];

        $items                  =   $request->only([
            'item_description', 'quantity', 'unit_measurement', 'unit_price', 'total_amount', 'type'
        ]);


        $split_upr              =   explode('-', $noa_model->upr->ref_number);
        $inputs['po_number']    =  "PO-".$split_upr[1]."-".$split_upr[2]."-".$split_upr[3]."-".$split_upr[4] ;

        $upr_model              =   $noa_model->upr;
        $inputs['prepared_by']  =   \Sentinel::getUser()->id;
        $inputs['rfq_id']       =   $noa_model->rfq_id;
        $inputs['upr_id']       =   $upr_model->id;
        $inputs['upr_number']   =   $upr_model->upr_number;
        $inputs['rfq_number']   =   $upr_model->rfq_number;

        if($upr_model->mode_of_procurement == 'public_bidding')
        {
            $inputs['bid_amount']   =   $noa_model->biddingWinner->bid_amount;
        }
        else
        {
            $inputs['bid_amount']   =   $noa_model->winner->bid_amount;
        }
        $inputs['status']       =   "pending";

        $result = $model->save($inputs);

        if($result)
        {
            for ($i=0; $i < count($items['item_description']); $i++) {
                $item_datas[]  =   [
                    'description'           =>  $items['item_description'][$i],
                    'quantity'              =>  $items['quantity'][$i],
                    'unit'                  =>  $items['unit_measurement'][$i],
                    'price_unit'            =>  $items['unit_price'][$i],
                    'total_amount'          =>  $items['total_amount'][$i],
                    'type'                  =>  $items['type'][$i],
                    'order_id'              =>  $result->id,
                ];
            }

            DB::table('purchase_order_items')->insert($item_datas);
        }

        $upr_result  = $upr->update([
            'status' => "PO Created",
            'delay_count'   => ($day_delayed > 2 )? $day_delayed - 2 : 0,
            'calendar_days' => $day_delayed + $upr_model->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $noa_model->upr_id);


        event(new Event($upr_result, $upr_result->ref_number." PO Created"));

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
        PORequest $request,
        PORepository $model,
        ItemRepository $items,
        BlankRFQRepository $rfq)
    {
        $items  =   $request->only(['item_description', 'quantity', 'unit_measurement', 'unit_price', 'total_amount']);
        $rfq_model              =   $rfq->with('upr')->findAwardeeById($request->rfq_id);

        $inputs                 =   $request->getData();
        $inputs['prepared_by']  =   \Sentinel::getUser()->id;
        $inputs['upr_id']       =   $rfq_model->upr_id;
        $inputs['upr_number']   =   $rfq_model->upr_number;
        $inputs['rfq_number']   =   $rfq_model->rfq_number;
        $inputs['bid_amount']   =   $rfq_model->bid_amount;
        $inputs['status']       =   "pending";
        $result = $model->save($inputs);

        $po_name   =   $rfq_model->upr->unit->name ."-". $rfq_model->upr->centers->name."-". $result->id;
        $po_name   =   str_replace(" ", "-", $po_name);

        $model->update(['po_number' => $po_name], $result->id);

        if($result)
        {
            for ($i=0; $i < count($items['item_description']); $i++) {
                $item_datas[]  =   [
                    'description'           =>  $items['item_description'][$i],
                    'quantity'              =>  $items['quantity'][$i],
                    'unit'                  =>  $items['unit_measurement'][$i],
                    'price_unit'            =>  $items['unit_price'][$i],
                    'total_amount'          =>  $items['total_amount'][$i],
                    'order_id'              =>  $result->id,
                ];
            }

            DB::table('purchase_order_items')->insert($item_datas);
        }

        $rfq->update(['status' => "PO Created"], $rfq_model->id);

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
        PORepository $model,
        RFQProponentRepository $proponents,
        SignatoryRepository $signatories,
        NOARepository $noa,
        UnitPurchaseRequestRepository $upr)
    {
        $result             =   $model->findById($id);
        $noa_model          =   $noa->with('winner')->findByUPR($result->upr_id);

        if($result->upr->mode_of_procurement == 'public_bidding')
        {
                $supplier           =   $noa_model->biddingWinner->supplier;
        }
        else
        {
                $supplier           =   $noa_model->winner->supplier;
        }

        $signatory_list     =   $signatories->lists('id','name');
        return $this->view('modules.biddings.purchase-order.show',[
            'data'          =>  $result,
            'signatory_list'=>  $signatory_list,
            'supplier'      =>  $supplier,
            'indexRoute'    =>  $this->baseUrl.'index',
            'editRoute'     =>  $this->baseUrl.'edit',
            'modelConfig'   =>  [
                'mfo_approval' =>  [
                    'route'     =>  [$this->baseUrl.'mfo-approved', $id],
                    'method'    =>  'POST'
                ],
                'pcco_approval' =>  [
                    'route'     =>  [$this->baseUrl.'pcco-approved', $id],
                    'method'    =>  'POST'
                ],
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update', $id],
                    'method'    =>  'PUT'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb($result->upr_number, 'biddings.unit-purchase-requests.show', $result->upr_id),
                new Breadcrumb('Purchase Order', 'biddings.purchase-orders.index')
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(
        $id,
        PaymentTermRepository $terms,
        SignatoryRepository $signatories,
        PORepository $model)
    {
        $result             =   $model->findById($id);
        $signatory_list     =   $signatories->lists('id','name');
        $term_lists         =   $terms->lists('id','name');

        return $this->view('modules.procurements.purchase-order.edit',[
            'data'          =>  $result,
            'term_lists'    =>  $term_lists,
            'signatory_list'=>  $signatory_list,
            'indexRoute'    =>  $this->baseUrl.'show',
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update-dates', $id],
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
                new Breadcrumb('Purchase Order', 'biddings.purchase-orders.show', $result->id),
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
        RFQProponentRepository $rfq,
        BlankRFQRepository $blank,
        PORepository $model
        )
    {
        $input  =   [
            'requestor_id'  =>  $request->requestor_id,
            'accounting_id' =>  $request->accounting_id,
            'approver_id'   =>  $request->approver_id,
            'coa_signatory' =>  $request->coa_signatory,
        ];

        $model->update($input, $id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateDates(
        Request $request,
        $id,
        RFQProponentRepository $rfq,
        BlankRFQRepository $blank,
        UnitPurchaseRequestRepository $upr,
        AuditLogRepository $audits,
        HolidayRepository $holidays,
        UserLogRepository $userLogs,
        UserRepository $users,
        SignatoryRepository $signatories,
        NOARepository $noa,
        PORepository $model
        )
    {

        $this->validate($request, [
            'requestor_id'  =>  'required',
            'accounting_id' =>  'required',
            'approver_id'   =>  'required',
            'coa_signatory' =>  'required',
        ]);

        $po_model =   $model->findById($id);

        $input  =   [
            'payment_term'          =>  $request->payment_term,
            'delivery_terms'        =>  $request->delivery_terms,
            'update_remarks'        =>  $request->update_remarks,
            'purchase_date'         =>  $request->purchase_date,
            'funding_released_date' =>  $request->funding_released_date,
            'funding_received_date' =>  $request->funding_received_date,
            'mfo_released_date'     =>  $request->mfo_released_date,
            'mfo_received_date'     =>  $request->mfo_received_date,
            'coa_approved_date'     =>  $request->coa_approved_date,
            'requestor_id'  =>  $request->requestor_id,
            'accounting_id' =>  $request->accounting_id,
            'approver_id'   =>  $request->approver_id,
            'coa_signatory' =>  $request->coa_signatory,
        ];

        if($po_model->requestor_id != $request->requestor_id)
        {
            $requestor  =   $signatories->findById($request->requestor_id);
            $input['requestor_signatory']   =   $requestor->name."/".$requestor->ranks."/".$requestor->branch."/".$requestor->designation;
        }

        if($po_model->accounting_id != $request->accounting_id)
        {
            $accounting  =   $signatories->findById($request->accounting_id);
            $input['accounting_signatory']   =   $accounting->name."/".$accounting->ranks."/".$accounting->branch."/".$accounting->designation;
        }

        if($po_model->approver_id != $request->approver_id)
        {
            $approver  =   $signatories->findById($request->approver_id);
            $input['approver_signatory']   =   $approver->name."/".$approver->ranks."/".$approver->branch."/".$approver->designation;
        }

        if($po_model->coa_signatory != $request->coa_signatory)
        {
            $coa  =   $signatories->findById($request->coa_signatory);
            $input['coa_name_signatory']   =   $coa->name."/".$coa->ranks."/".$coa->branch."/".$coa->designation;
        }

        $result =   $model->update($input, $id);

        $modelType  =   'Revlv\Procurements\PurchaseOrder\POEloquent';
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
    public function destroy($id, PORepository $model)
    {
        $model->delete($id);

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }

    /**
     * [viewPrint description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrintTerms($id, PORepository $model)
    {
        $result                     =  $model->with(['rfq'])->findById($id);
        $data['transaction_date']   =  $result->rfq->transaction_date;
        $data['rfq_number']         =  $result->rfq_number;
        $data['header']             =  $result->upr->centers;

        $header                     =  $headers->findByUnit($result->upr->units);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        if($result->upr->centers)
        {
            $data['unit'] = $result->upr->centers->name;
        }
        if($result->upr->unit){
            $data['unit'] = $result->upr->unit->description;
        }

        $pdf = PDF::loadView('forms.po-terms', ['data' => $data])->setOption('margin-bottom', 0)->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('po-terms.pdf');
        return $pdf->download('po-terms.pdf');
    }


    /**
     * [viewPrint description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrint($id, PORepository $model, NOARepository $noa, UnitPurchaseRequestRepository $upr)
    {
        $result                     =  $model->with(['terms','delivery','rfq','items'])->findById($id);
        $upr_model                  =  $upr->findById($result->upr_id);

        if($upr_model->mode_of_procurement == 'public_bidding')
        {
            $noa_model                  =  $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $noa_model                  =  $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        }

        if($result->coa_signatories == null || $result->requestor == null || $result->accounting == null )
        {
            return redirect()->back()->with(['error' => 'Please add signatories']);
        }

        $data['po_number']          =  $result->po_number;
        $data['account']            =  $result->type;
        $data['purchase_date']      =  $result->purchase_date;
        $data['transaction_date']   =  $result->rfq->transaction_date;
        $data['winner']             =  $noa_model;
        $data['rfq_number']         =  $result->rfq_number;
        $data['mode']               =  $upr_model->modes->name;
        $data['term']               =  $result->terms->name;
        $data['accounts']           =  $upr_model->accounts->new_account_code;
        $data['centers']            =  $upr_model->centers->name;
        $data['purpose']            =  $upr_model->purpose;
        $data['delivery']           =  $result->delivery;
        $data['delivery_term']      =  $result->delivery_terms;
        $data['items']              =  $result->items;
        $data['bid_amount']         =  $result->bid_amount;
        $data['requestor']          =  $result->requestor;
        $data['accounting']         =  $result->accounting;
        $data['approver']           =  $result->approver;
        $data['coa_signatories']    =  $result->coa_signatories;
        $data['mfo_release_date']   =  $result->mfo_release_date;
        $data['coa_approved_date']  =  $result->coa_approved_date;
        $data['funding_release_date']  =  $result->funding_release_date;

        $pdf = PDF::loadView('forms.po', ['data' => $data])
            ->setOption('margin-bottom', 30)
            ->setOption('footer-html', route('pdf.footer'))
            ->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('po.pdf');
    }



    /**
     * [viewPrint description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrint3($id, PORepository $model, NOARepository $noa, UnitPurchaseRequestRepository $upr, HeaderRepository $headers, PCCOHeaderRepository $pccoHeaders)
    {
        $result                     =  $model->with(['terms','delivery','rfq','items'])->findById($id);
        $upr_model                  =  $upr->findById($result->upr_id);

        $header                     =  $pccoHeaders->findByPCCO($result->upr->procurement_office);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;

        if($upr_model->mode_of_procurement == 'public_bidding')
        {
            $noa_model                  =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $noa_model                  =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        } ;

        if($result->coa_signatories == null || $result->requestor == null || $result->accounting == null )
        {
            return redirect()->back()->with(['error' => 'Please add signatories']);
        }
        $data['type']               =  $result->type;
        $data['account']            =  $result->type;
        $data['po_number']          =  $result->po_number;
        $data['upr_number']         =  $upr_model->upr_number;
        $data['remarks']            =  $result->remarks;
        $data['purchase_date']      =  $result->purchase_date;
        // $data['transaction_date']   =  $result->rfq->transaction_date;
        $data['winner']             =  $noa_model;
        $data['rfq_number']         =  $result->rfq_number;
        $data['mode']               =  ($upr_model->modes != null) ?$upr_model->modes->name : "Public Bidding";
        $data['term']               =  $result->terms->name;
        // $data['accounts']           =  $upr_model->accounts->new_account_code;
        $data['centers']            =  $upr_model->centers->name;
        $data['unit']               =  $result->upr->unit->short_code;
        $data['purpose']            =  $upr_model->purpose;
        $data['delivery']           =  $result->delivery;
        $data['delivery_term']      =  $result->delivery_terms;
        $data['items']              =  $result->items;
        $data['bid_amount']         =  $result->bid_amount;

        $data['requestor']          =  explode('/', $result->requestor_signatory);
        $data['accounting']         =  explode('/', $result->accounting_signatory);
        $data['approver']           =  explode('/', $result->approver_signatory);
        $data['coa_signatories']    =  explode('/', $result->coa_name_signatory);

        $data['mfo_release_date']   =  $result->mfo_released_date;
        $data['coa_approved_date']  =  $result->coa_approved_date;
        $data['funding_release_date']  =  $result->funding_release_date;
        $data['header']             =   $upr_model->centers;


        $pdf = PDF::loadView('forms.po3', ['data' => $data])
            ->setOption('margin-bottom', 30)
            ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('po.pdf');
    }

    /**
     * [viewPrint description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrintCOA($id, PORepository $model, NOARepository $noa)
    {
        $result                     =  $model->with(['coa_signatories','rfq','upr'])->findById($id);

        if($upr_model->mode_of_procurement == 'public_bidding')
        {
            $noa_model                  =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $noa_model                  =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        }

        if($result->coa_signatories == null)
        {
            return redirect()->back()->with(['error' => 'Please add signatory for COA']);
        }

        $data['transaction_date']   =  $result->coa_approved_date;
        $data['rfq_number']         =  $result->rfq_number;
        $data['po_number']         =  $result->po_number;
        $data['bid_amount']         =  $result->bid_amount;
        $data['project_name']       =  $result->upr->project_name;
        $data['winner']             =  $noa_model->name;
        $data['coa_signatory']      =  $result->coa_signatories;

        $pdf = PDF::loadView('forms.po-coa', ['data' => $data])->setOption('margin-bottom', 0)->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('po-coa.pdf');
        return $pdf->download('po-coa.pdf');
    }

    /**
     * [downloadCoa description]
     *
     * @param  [type]       $id    [description]
     * @param  PORepository $model [description]
     * @return [type]              [description]
     */
    public function downloadCoa($id, PORepository $model)
    {
        $result         = $model->findById($id);

        $directory      =   storage_path("app/coa-approved-attachments/".$result->coa_file);

        if(!file_exists($directory))
        {
            return 'Sorry. File does not exists.';
        }

        return response()->download($directory);
    }


    /**
     * [viewLogs description]
     *
     * @param  [type]             $id    [description]
     * @param  BlankRFQRepository $model [description]
     * @return [type]                    [description]
     */
    public function viewLogs($id, PORepository $model, AuditLogRepository $logs)
    {

        $modelType  =   'Revlv\Procurements\PurchaseOrder\POEloquent';
        $result     =   $logs->findByModelAndId($modelType, $id);
        $data_model =   $model->findById($id);

        return $this->view('modules.procurements.purchase-order.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'model'         =>  $data_model,
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb('Purchase Order', 'biddings.purchase-orders.show', $data_model->id),
                new Breadcrumb('Logs'),
            ]
        ]);
    }
}
