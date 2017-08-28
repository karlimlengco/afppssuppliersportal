<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use PDF;
use Carbon\Carbon;
use \App\Support\Breadcrumb;
use Validator;
use App\Events\Event;

use \Revlv\Procurements\DeliveryOrder\DeliveryOrderRepository;
use \Revlv\Procurements\NoticeOfAward\NOARepository;
use \Revlv\Procurements\DeliveryOrder\DeliveryOrderRequest;
use \Revlv\Procurements\DeliveryOrder\Items\ItemRepository;
use \Revlv\Procurements\PurchaseOrder\PORepository;
use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\Holidays\HolidayRepository;
use Revlv\Procurements\DeliveryOrder\AttachmentTrait;
use \Revlv\Users\Logs\UserLogRepository;
use \Revlv\Users\UserRepository;

class DeliveryController extends Controller
{
    use AttachmentTrait;

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "procurements.delivery-orders.";

    /**
     * [$model description]
     *
     * @var [type]
     */
    protected $model;

    /**
     * [$po description]
     *
     * @var [type]
     */
    protected $po;
    protected $noa;
    protected $items;
    protected $upr;
    protected $signatories;
    protected $audits;
    protected $holidays;
    protected $users;
    protected $userLogs;


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
    public function getDatatable(DeliveryOrderRepository $model)
    {
        return $model->getDatatable();
    }


    /**
     * [getListDatatable description]
     *
     * @return [type]            [description]
     */
    public function getListDatatable(DeliveryOrderRepository $model, $id)
    {
        return $model->getListDatatable($id);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.procurements.delivery.index',[
            'createRoute'   =>  $this->baseUrl."create",
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('Delivery', 'procurements.delivery-orders.index')
            ]
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeliveryOrderRequest $request, DeliveryOrderRepository $model)
    {
        $result = $model->save($request->getData());

        return redirect()->route($this->baseUrl.'edit', $result->id)->with([
            'success'  => "New record has been successfully added."
        ]);        //
    }

    /**
     * [createFromPurchase description]
     *
     * @param  [type]  $id      [description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function createFromPurchase($id,
        Request $request,
        DeliveryOrderRepository $model,
        UnitPurchaseRequestRepository $upr,
        PORepository $po,
        HolidayRepository $holidays)
    {
        $po_model               =   $po->with(['items'])->findById($id);
        $ntp                    =   $po_model->ntp;

        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('transaction_date') );
        $ntp_date               =   Carbon::createFromFormat('!Y-m-d', $ntp->award_accepted_date );
        $cd                     =   $ntp_date->diffInDays($transaction_date);

        $day_delayed            =   $ntp_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed > $po_model->delivery_terms)
        {
            $day_delayed  =  $day_delayed - $po_model->delivery_terms;
        }


        $validator = Validator::make($request->all(),[
            'expected_date'     =>  'required|after_or_equal:'. $ntp_date->format('Y-m-d'),
            'transaction_date'  =>  'required|after_or_equal:'. $ntp_date->format('Y-m-d'),
            'action'            =>  'required_with:remarks',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request, $po_model) {
            if ( $request->get('remarks') == null && $day_delayed > $po_model->delivery_terms) {
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
        $inputs     =   [
            'expected_date'     =>  $request->expected_date,
            'transaction_date'  =>  $request->transaction_date,
            'po_id'             =>  $id,
            'rfq_id'            =>  $po_model->rfq_id,
            'upr_id'            =>  $po_model->upr_id,
            'rfq_number'        =>  $po_model->rfq_number,
            'status'            =>  'ongoing',
            'upr_number'        =>  $po_model->upr_number,
            'created_by'        =>  \Sentinel::getUser()->id,
            'days'              => $wd,
            'action'           =>  $request->action,
            'remarks'           =>  $request->remarks
        ];


        $result = $model->save($inputs);

        $items  =   [];

        foreach ($po_model->items as $item) {
            $items[]  =   [
                'order_id'      =>  $result->id,
                'description'   =>  $item->description,
                'quantity'      =>  $item->quantity,
                'unit'          =>  $item->unit,
                'price_unit'    =>  $item->price_unit,
                'total_amount'  =>  $item->total_amount,
            ];
        }

        DB::table('delivery_order_items')->insert($items);

        $upr_result  = $upr->update([
            'next_allowable'=> $po_model->delivery_terms,
            'next_step'     => 'Receive Delivery',
            'next_due'      => $ntp_date->addDays($po_model->delivery_terms),
            'last_date'     => $transaction_date,
            'status'        => 'NOD Created',
            'delay_count'   => $wd,
            'calendar_days' => $cd + $result->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr_id);

        event(new Event($upr_result, $upr_result->ref_number." NOD Created"));

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
    public function show($id,
        DeliveryOrderRepository $model,
        SignatoryRepository $signatories)
    {
        $result             =   $model->with(['items'])->findById($id);

        $signatory_list     =   $signatories->lists('id','name');

        return $this->view('modules.procurements.delivery.show',[
            'data'          =>  $result,
            'signatory_list'=>  $signatory_list,
            'indexRoute'    =>  $this->baseUrl.'index',
            'editRoute'     =>  $this->baseUrl.'edit',
            'editDateRoute' =>  $this->baseUrl.'edit-dates',
            'completeRoute' =>  $this->baseUrl.'completed',
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update-signatory', $id],
                    'method'    =>  'PUT',
                    'novalidate'    => 'novalidate'
                ],
                'add_attachment' =>  [
                    'route'     =>  [$this->baseUrl.'attachments.store', $id],
                    'method'    =>  'PUT'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb($result->upr_number, 'procurements.unit-purchase-requests.show', $result->upr_id),
                new Breadcrumb('Delivery', 'procurements.delivery-orders.index')
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, DeliveryOrderRepository $model)
    {
        $result =   $model->with(['items'])->findById($id);

        return $this->view('modules.procurements.delivery.edit',[
            'data'          =>  $result,
            'indexRoute'    =>  $this->baseUrl.'index',
            'showRoute'     =>  $this->baseUrl.'show',
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update', $id],
                    'method'    =>  'PUT',
                    'novalidate'=>  'novalidate'
                ],
                'destroy'   => [
                    'route' => [$this->baseUrl.'destroy',$id],
                    'method'=> 'DELETE',
                    'novalidate'=>  'novalidate'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('Delivery', 'procurements.delivery-orders.show', $result->id),
                new Breadcrumb('Update'),
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editDates($id, DeliveryOrderRepository $model, SignatoryRepository $signatories)
    {
        $result =   $model->with(['items'])->findById($id);
        $signatory_list     =   $signatories->lists('id','name');

        return $this->view('modules.procurements.delivery.edit-dates',[
            'data'          =>  $result,
            'signatory_list'=>  $signatory_list,
            'indexRoute'    =>  $this->baseUrl.'index',
            'showRoute'     =>  $this->baseUrl.'show',
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update-dates', $id],
                    'method'    =>  'PUT',
                    'novalidate'    => 'novalidate'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('Delivery', 'procurements.delivery-orders.show', $result->id),
                new Breadcrumb('Update'),
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
    public function updateDates(
        Request $request,
        UnitPurchaseRequestRepository $upr,
        AuditLogRepository $audits,
        HolidayRepository $holidays,
        UserLogRepository $userLogs,
        UserRepository $users,
        SignatoryRepository $signatories,
        PORepository $po,
        $id,
        DeliveryOrderRepository $model)
    {

        $this->validate($request, [
            "update_remarks"        => 'required',
            "expected_date"         => 'required',
            "signatory_id"         => 'required',
            "transaction_date"      => 'required',
            // "date_delivered_to_coa" => 'required',
        ]);

        $dr_model   =   $model->findById($id);

        $input  =   [
            "update_remarks"        =>  $request->update_remarks,
            "expected_date"         =>  $request->expected_date,
            "delivery_date"         =>  $request->delivery_date,
            "transaction_date"      =>  $request->transaction_date,
            "signatory_id"      =>  $request->signatory_id,
            // "date_delivered_to_coa" =>  $request->date_delivered_to_coa,
        ];

        if($dr_model->signatory_id != $request->signatory_id)
        {
            $signatory  =   $signatories->findById($request->signatory_id);
            $input['signatory']   =   $signatory->name."/".$signatory->ranks."/".$signatory->branch."/".$signatory->designation;
        }

        $result = $model->update($input, $id);

        $po_model               =   $po->with(['items'])->findById($result->po_id);
        $ntp                    =   $po_model->ntp;

        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('transaction_date') );
        $ntp_date               =   Carbon::createFromFormat('!Y-m-d', $ntp->award_accepted_date );
        $cd                     =   $ntp_date->diffInDays($transaction_date);

        $day_delayed            =   $ntp_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed > $po_model->delivery_terms)
        {
            $day_delayed  =  $day_delayed - $po_model->delivery_terms;
        }

        if($wd != $result->days)
        {
            $model->update(['days' => $wd], $id);
        }

        $modelType  =   'Revlv\Procurements\DeliveryOrder\DeliveryOrderEloquent';
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(
        $id,
        ItemRepository $items,
        DeliveryOrderRequest $request,
        DeliveryOrderRepository $model,
        UnitPurchaseRequestRepository $upr,
        HolidayRepository $holidays)
    {
        $inputs                 =   $request->getData();

        $dr_model               =   $model->findById($id);
        $ntp                    =   $dr_model->upr->ntp;

        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('delivery_date') );
        $dr_date                =   Carbon::createFromFormat('!Y-m-d', $ntp->award_accepted_date );
        $cd                     =   $dr_date->diffInDays($transaction_date);

        $day_delayed            =   $dr_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;
        if($day_delayed > $dr_model->po->delivery_terms)
        {
            $day_delayed = $day_delayed - $dr_model->po->delivery_terms;
        }

        $validator = Validator::make($request->all(),[
            'delivery_date'     =>  'required|after_or_equal:'. $dr_date->format('Y-m-d'),
            // 'received_quantity.*' => 'required'
        ]);

        $validator->after(function ($validator)use($day_delayed, $request, $dr_model) {
            if ( $request->get('delivery_remarks') == null && $day_delayed > $dr_model->po->delivery_terms) {
                $validator->errors()->add('delivery_remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('delivery_action') == null && $day_delayed > $dr_model->po->delivery_terms) {
                $validator->errors()->add('delivery_action', 'This action is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Check all your fields'])
                        ->withErrors($validator)
                        ->withInput();
        }

        $inputs['delivery_days']    =   $wd;
        $inputs['delivery_remarks'] =   $request->delivery_remarks;
        $inputs['delivery_action']  =   $request->delivery_action;

        // dd($request->all());

        // Delay
        $item_input =   $request->only(['ids', 'received_quantity']);

        $errcount = 0;
        $new_item  =   [];
        for ($i=0; $i < count($item_input['ids']) ; $i++) {
            $item_model =  $items->getById($item_input['ids'][$i]);


            if($item_model->quantity != $item_input['received_quantity'][$i])
            {
                $new_quantity       =   $item_model->quantity - $item_input['received_quantity'][$i];
                $new_item[]  =   [
                    'order_id'      =>  $item_model->order_id,
                    'description'   =>  $item_model->description,
                    'quantity'      =>  $new_quantity,
                    'unit'          =>  $item_model->unit,
                    'price_unit'    =>  $item_model->price_unit,
                    'total_amount'  =>  $item_model->price_unit * $new_quantity,
                ];
                $errcount ++;
            }


            $items->update([
                'quantity'          =>  $item_input['received_quantity'][$i],
                'received_quantity' =>  $item_input['received_quantity'][$i]
            ], $item_input['ids'][$i]);

        }
        //

        $inputs['received_by']  =   \Sentinel::getUser()->id;


        if($errcount != 0)
        {
            $status =   'Delivery Partial';

            $dr_inputs     =   [
                'expected_date'     =>  $dr_model->expected_date,
                'transaction_date'  =>  $dr_model->transaction_date,
                'po_id'             =>  $dr_model->po_id,
                'rfq_id'            =>  $dr_model->rfq_id,
                'upr_id'            =>  $dr_model->upr_id,
                'rfq_number'        =>  $dr_model->rfq_number,
                'status'            =>  'ongoing',
                'upr_number'        =>  $dr_model->upr_number,
                'created_by'        =>  $dr_model->created_by,
                'days'              =>  $dr_model->days,
            ];

            $new_dr =   $model->save($dr_inputs);

            foreach($new_item as $nitem)
            {
                $nitem['order_id'] = $new_dr->id;
                $items->save($nitem);
            }
        }
        else
        {
            $status =   'Delivery Received';
        }
        $inputs['status']       =   $status;

        $model->update($inputs, $id);

        $upr_result  = $upr->update([
            'next_allowable'=> 1,
            'next_step'     => 'COA Delivery',
            'next_due'      => $transaction_date->addDays(1),
            'last_date'     => $transaction_date,
            'status'        => $status,
            'delay_count'   => $day_delayed,
            'calendar_days' => $cd + $dr_model->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $dr_model->upr_id);


        event(new Event($upr_result, $upr_result->ref_number." ". $status));


        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }


    /**
     * [updateSignatory description]
     *
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function updateSignatory(
        Request $request, $id,
        DeliveryOrderRepository $model)
    {
        $this->validate($request, [
            'signatory_id'   =>  'required',
        ]);

        $model->update(['signatory_id' => $request->signatory_id], $id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [completeOrder description]
     *
     * @param  [type]                  $id    [description]
     * @param  DeliveryOrderRepository $model [description]
     * @return [type]                         [description]
     */
    public function completeOrder(
        Request $request, $id,
        DeliveryOrderRepository $model, UnitPurchaseRequestRepository $upr,
        HolidayRepository $holidays)
    {
        $date_completed     =   \Carbon\Carbon::now();

        $dr_model               =   $model->findById($id);

        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('date_delivered_to_coa') );
        $dr_date                =   Carbon::createFromFormat('!Y-m-d', $dr_model->delivery_date );
        $cd                     =   $dr_date->diffInDays($transaction_date);

        $day_delayed            =   $dr_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;
        if($day_delayed > 1)
        {
            $day_delayed = $day_delayed - 1;
        }

        $validator = Validator::make($request->all(),[
            'date_delivered_to_coa'   =>  'required|after_or_equal:'. $dr_date->format('Y-m-d'),
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('dr_coa_remarks') == null && $day_delayed > 1) {
                $validator->errors()->add('dr_coa_remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('dr_coa_action') == null && $day_delayed > 1) {
                $validator->errors()->add('dr_coa_action', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Please Check Your Fields.'])
                        ->withErrors($validator)
                        ->withInput();
        }
        $inputs['dr_coa_days']          =   $wd;
        $inputs['dr_coa_remarks']       =   $request->dr_coa_remarks;
        $inputs['dr_coa_action']        =   $request->dr_coa_action;
        $inputs['date_delivered_to_coa']=   $request->date_delivered_to_coa;
        $inputs['delivered_to_coa_by']  =   \Sentinel::getUser()->id;
        $inputs['status']               =   'completed';
        // Delay

        $result =   $model->update($inputs, $id);

        $upr_result = $upr->update([
            'next_allowable'=> 2,
            'next_step'     => 'Technical Inspection',
            'next_due'      => $transaction_date->addDays(2),
            'last_date'     => $transaction_date,
            'status'        => 'Complete COA Delivery',
            'delay_count'   => $day_delayed,
            'calendar_days' => $cd + $dr_model->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr_id);


        event(new Event($upr_result, $upr_result->ref_number." Complete COA Delivery"));

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
    public function destroy($id, DeliveryOrderRepository $model)
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
    public function viewPrint(
        $id,
        DeliveryOrderRepository $model,
        NOARepository $noa
        )
    {
        $result                     =  $model->with(['signatory','upr', 'po'])->findById($id);

        if($result->signatory == null)
        {
            return redirect()->back()->with([
                'error'  => "Please select signatory first"
            ]);
        }

        // $noa_model                  =   $noa->with('winner')->findByRFQ($result->rfq_id)->winner->supplier;

        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $noa_model                  =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $noa_model                  =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        }

        $data['transaction_date']   =  $result->delivery_date;
        $data['po_number']          =  $result->po->po_number;
        $data['bid_amount']         =  $result->po->bid_amount;
        $data['po_type']            =  $result->po->type;
        $data['project_name']       =  $result->upr->project_name;
        $data['center']             =  $result->upr->centers->name;
        $data['items']              =  $result->upr->items;
        $data['signatory']          =  explode('/',$result->signatory);
        $data['winner']             =  $noa_model->name;
        $data['expected_date']      =  $result->expected_date;
        $data['header']             =  $result->upr->centers;

        $pdf = PDF::loadView('forms.nod', ['data' => $data])
            ->setOption('margin-bottom', 30)
            ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('nod.pdf');
    }

    /**
     * [viewLogs description]
     *
     * @param  [type]             $id    [description]
     * @param  BlankRFQRepository $model [description]
     * @return [type]                    [description]
     */
    public function viewLogs($id, DeliveryOrderRepository $model, AuditLogRepository $logs)
    {

        $modelType  =   'Revlv\Procurements\DeliveryOrder\DeliveryOrderEloquent';
        $result     =   $logs->findByModelAndId($modelType, $id);
        $data_model =   $model->findById($id);

        return $this->view('modules.procurements.delivery.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'model'         =>  $data_model,
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('Delivery', 'procurements.delivery-orders.show', $data_model->id),
                new Breadcrumb('Logs'),
            ]
        ]);
    }

    /**
     * [listsAll description]
     *
     * @param  [type]             $id    [description]
     * @param  BlankRFQRepository $model [description]
     * @return [type]                    [description]
     */
    public function listsAll($id, UnitPurchaseRequestRepository $model)
    {
        $data_model =   $model->findById($id);

        \JavaScript::put([
            'deliveryOrder'   => $data_model->id,
        ]);

        return $this->view('modules.procurements.delivery.lists',[
            'model'         =>  $data_model,
            'id'            =>  $data_model->id,
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb($data_model->upr_number, 'procurements.unit-purchase-requests.show', $data_model->id),
                new Breadcrumb('Delivery Lists')
            ]
        ]);
    }

    /**
     * [GetAllItems description]
     * @param [type]                  $id    [description]
     * @param DeliveryOrderRepository $model [description]
     */
    public function GetAllItems($id, DeliveryOrderRepository $model)
    {
        $model_items=   $model->getAllItems($id);

        $response   = [
            'data' => $model_items
        ];

        return $response;
    }

    /**
     * [GetAllItems description]
     * @param [type]                  $id    [description]
     * @param DeliveryOrderRepository $model [description]
     */
    public function GetItemOrders(Request $request, $uprID, $item = null, DeliveryOrderRepository $model)
    {
        $model_items=   $model->GetItemOrders($uprID, $request->item);

        $response   = [
            'data' => $model_items,
            'item' => $request->item
        ];

        return $response;
    }



}
