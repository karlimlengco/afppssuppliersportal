<?php

namespace Revlv\Controllers\Biddings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use PDF;
use \App\Support\Breadcrumb;
use Carbon\Carbon;
use App\Events\Event;
use Validator;

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
    protected $baseUrl  =   "biddings.delivery-orders.";

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
        return $model->getDatatable('bidding');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.biddings.delivery.index',[
            'createRoute'   =>  $this->baseUrl."create",
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb('Delivery', 'biddings.delivery-orders.index')
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
            'expected_date'     =>  'required',
            'transaction_date'  =>  'required',
            'action'  =>  'required_with:remarks',
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
            'days'              =>  $wd,
            'action'           =>  $request->action,
            'remarks'           =>  $request->remarks
        ];


        $result = $model->save($inputs);

        $items  =   [];

        foreach ($po_model->items as $item) {
            $items[]  =   [
                'id'            =>  (string) \Uuid::generate(),
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
            'next_due'      => $transaction_date->addDays($po_model->delivery_terms),
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

        return $this->view('modules.biddings.delivery.show',[
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
                    'novalidate'=> 'novalidate'
                ],
                'add_attachment' =>  [
                    'route'     =>  [$this->baseUrl.'attachments.store', $id],
                    'method'    =>  'PUT'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb($result->upr_number, 'biddings.unit-purchase-requests.show', $result->upr_id),
                new Breadcrumb('Delivery', 'biddings.delivery-orders.index')
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

        return $this->view('modules.biddings.delivery.edit',[
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
                    'method'=> 'DELETE'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Public Bidding'),
                new Breadcrumb('Delivery', 'biddings.delivery-orders.show', $result->id),
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

        return $this->view('modules.biddings.delivery.edit-dates',[
            'data'          =>  $result,
            'signatory_list'=>  $signatory_list,
            'indexRoute'    =>  $this->baseUrl.'index',
            'showRoute'     =>  $this->baseUrl.'show',
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update-dates', $id],
                    'method'    =>  'PUT',
                    'novalidate'=> 'novalidate'
                ]
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
        SignatoryRepository $signatories,
        UserRepository $users,
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
        $ntp                    =   $dr_model->po->ntp;

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
            'delivery_date'     =>  'required',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('delivery_remarks') == null && $day_delayed > 7) {
                $validator->errors()->add('delivery_remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('delivery_action') == null && $day_delayed > 7) {
                $validator->errors()->add('delivery_action', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Please Check Your Fields.'])
                        ->withErrors($validator)
                        ->withInput();
        }
        $inputs['delivery_days']    =   $wd;
        $inputs['delivery_remarks'] =   $request->delivery_remarks;
        $inputs['delivery_action']  =   $request->delivery_action;

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

        $model->update(['signatory_id' =>$request->signatory_id], $id);

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

        $day_delayed            =   $dr_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $validator = Validator::make($request->all(),[
            'date_delivered_to_coa'   =>  'required',
            'dr_coa_action'  =>  'required_with:dr_coa_remarks',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('dr_coa_remarks') == null && $day_delayed > 1) {
                $validator->errors()->add('dr_coa_remarks', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Please Check Your Fields.'])
                        ->withErrors($validator)
                        ->withInput();
        }
        $inputs['dr_coa_days']          =   $day_delayed;
        $inputs['dr_coa_remarks']       =   $request->dr_coa_remarks;
        $inputs['dr_coa_action']       =   $request->dr_coa_action;
        $inputs['date_delivered_to_coa']=   $request->date_delivered_to_coa;
        $inputs['delivered_to_coa_by']  =   \Sentinel::getUser()->id;
        $inputs['status']               =   'completed';
        // Delay

        $result =   $model->update($inputs, $id);

        $upr->update([
            'status' => 'Complete COA Delivery',
            'delay_count'   => ($day_delayed > 1 )? $day_delayed - 1 : 0,
            'calendar_days' => $day_delayed + $dr_model->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr_id);

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
            $noa_model           =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $noa_model           =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        }

        $data['transaction_date']   =  $result->delivery_date;
        $data['po_number']          =  $result->po->po_number;
        $data['bid_amount']         =  $result->po->bid_amount;
        $data['project_name']       =  $result->upr->project_name;
        $data['center']             =  $result->upr->centers->name;
        $data['signatory']          =  $result->signatory;
        $data['winner']             =  $noa_model->name;
        $data['expected_date']      =  $result->expected_date;

        $pdf = PDF::loadView('forms.nod', ['data' => $data])
            ->setOption('margin-bottom', 30)
            ->setOption('footer-html', route('pdf.footer'))
            ->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('nod.pdf');
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

        return $this->view('modules.biddings.delivery.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'model'         =>  $data_model
        ]);
    }

}
