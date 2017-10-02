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

use \Revlv\Procurements\NoticeOfAward\NOARepository;
use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Procurements\DeliveryOrder\DeliveryOrderRepository;
use \Revlv\Procurements\InspectionAndAcceptance\InspectionAndAcceptanceRepository;
use \Revlv\Procurements\InspectionAndAcceptance\InspectionAndAcceptanceRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\RFQProponents\RFQProponentRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\Holidays\HolidayRepository;
use \Revlv\Users\Logs\UserLogRepository;
use \Revlv\Users\UserRepository;
use \Revlv\Settings\Forms\Header\HeaderRepository;

class InspectionAndAcceptanceController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "procurements.inspection-and-acceptance.";

    /**
     * [$model description]
     *
     * @var [type]
     */
    protected $model;

    /**
     * [$devivery description]
     *
     * @var [type]
     */
    protected $devivery;
    protected $noa;
    protected $signatories;
    protected $upr;
    protected $rfq;
    protected $audits;
    protected $holidays;
    protected $users;
    protected $userLogs;
    protected $headers;

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
    public function getDatatable(InspectionAndAcceptanceRepository $model)
    {
        return $model->getDatatable();
    }

    /**
     * [acceptDelivery description]
     *
     * @return [type] [description]
     */
    public function acceptOrder(Request $request, $id, InspectionAndAcceptanceRepository $model, DeliveryOrderRepository $delivery, UnitPurchaseRequestRepository $upr, HolidayRepository $holidays)
    {
        $tiac                   =   $model->findById($id);
        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('accepted_date') );
        $tiac_date              =   Carbon::createFromFormat('!Y-m-d', $tiac->inspection_date );
        $cd                     =   $tiac_date->diffInDays($transaction_date);

        $day_delayed            =   $tiac_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed > 1)
        {
            $day_delayed = $day_delayed - 1;
        }

        $validator = Validator::make($request->all(),[
            'accepted_date'       => 'required|after_or_equal:'. $tiac_date->format('Y-m-d'),
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('accept_remarks') == null && $day_delayed > 1) {
                $validator->errors()->add('accept_remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('accept_action') == null && $day_delayed > 1) {
                $validator->errors()->add('accept_remarks', 'This field is required when your process is delay');
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
        $inputs         =   [
            'accepted_date'     =>  $request->accepted_date,
            'status'            =>  'Accepted',
            'accepted_by'       =>  \Sentinel::getUser()->id,
            'accept_days'       =>  $wd,
            'accept_remarks'    =>  $request->accept_remarks,
            'accept_action'    =>  $request->accept_action
        ];

        $result =   $model->update($inputs, $id);

        $delivery->update(['status' => 'Accepted'], $result->dr_id);

        $upr_result  =  $upr->update([
            'next_allowable'=> 1,
            'next_step'     => 'DIIR',
            'next_due'      => $transaction_date->addDays(1),
            'last_date'     => $transaction_date,
            'status'        => 'Inspection Accepted',
            'delay_count'   => $day_delayed,
            'calendar_days' => $cd + $result->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr_id);

        event(new Event($upr_result, $upr_result->ref_number." Inspection Accepted"));

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.procurements.inspection-acceptance.index',[
            'createRoute'   =>  $this->baseUrl."create",
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('TIAC', 'procurements.inspection-and-acceptance.index')
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(DeliveryOrderRepository $delivery)
    {
        $delivery_lists =   $delivery->listCompleted('id', 'delivery_number');
        $this->view('modules.procurements.inspection-acceptance.create',[
            'delivery_lists'=>  $delivery_lists,
            'indexRoute'    =>  $this->baseUrl.'index',
            'modelConfig'   =>  [
                'store' =>  [
                    'route'     =>  $this->baseUrl.'store'
                ]
            ]
        ]);
    }

    /**
     * [createFromDelivery description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function createFromDelivery($id)
    {

        $this->view('modules.procurements.inspection-acceptance.create-from-dr',[
            'indexRoute'    =>  'procurements.delivery-orders.show',
            'id'            =>  $id,
            'modelConfig'   =>  [
                'store' =>  [
                    'route'     =>  [$this->baseUrl.'create-from-delivery.store', $id]
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('TIAC')
            ]
        ]);
    }

    /**
     * [storeFromDelivery description]
     *
     * @param  [type]                            $id       [description]
     * @param  InspectionAndAcceptanceRequest    $request  [description]
     * @param  DeliveryOrderRepository           $delivery [description]
     * @param  InspectionAndAcceptanceRepository $model    [description]
     * @return [type]                                      [description]
     */
    public function storeFromDelivery(
        $id,
        Request $request,
        DeliveryOrderRepository $delivery,
        UnitPurchaseRequestRepository $upr,
        InspectionAndAcceptanceRepository $model,
        HolidayRepository $holidays)
    {
        $dr_id                  =   $id;

        $dr_model               =   $delivery->findById($dr_id);

        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('inspection_date') );
        $dr_date                =   Carbon::createFromFormat('!Y-m-d', $dr_model->date_delivered_to_coa );
        $cd                     =   $dr_date->diffInDays($transaction_date);

        $day_delayed            =   $dr_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed > 2)
        {
            $day_delayed = $day_delayed - 2;
        }


        $validator = Validator::make($request->all(),[
            'inspection_date'       => 'required|after_or_equal:'. $dr_date->format('Y-m-d'),
            'invoice_number.*' => 'required'
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('remarks') == null && $day_delayed > 2) {
                $validator->errors()->add('remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('action') == null && $day_delayed > 2) {
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
        $items                      =   $request->only(['invoice_number', 'invoice_date']);

        $inputs                     =   [
            'inspection_date'   =>  $request->inspection_date,
            'nature_of_delivery'=>  $request->nature_of_delivery,
            'recommendation'    =>  $request->recommendation,
            'findings'          =>  $request->findings,
            'remarks'           =>  $request->remarks,
            'action'           =>  $request->action,
            'days'              =>  $wd,
        ];

        $inputs['dr_id']            =   $dr_model->id;
        $inputs['rfq_id']           =   $dr_model->rfq_id;
        $inputs['upr_id']           =   $dr_model->upr_id;
        $inputs['rfq_number']       =   $dr_model->rfq_number;
        $inputs['upr_number']       =   $dr_model->upr_number;
        $inputs['delivery_number']  =   $dr_model->delivery_number;
        $inputs['prepared_by']      =   \Sentinel::getUser()->id;
        $inputs['status']           =   'ongoing';

        $result = $model->save($inputs);
        if($result)
        {
            $invoices                   =   [];
            for ($i=0; $i < count($items['invoice_number']); $i++) {
                $invoices[]  =   [

                    'id'                =>  (string) \Uuid::generate(),
                    'invoice_number'    =>  $items['invoice_number'][$i],
                    'invoice_date'      =>  $items['invoice_date'][$i],
                    'acceptance_id'     =>  $result->id,
                ];
            }

            DB::table('inspection_acceptance_invoices')->insert($invoices);
        }

        $upr_result = $upr->update([
            'next_allowable'=> 2,
            'next_step'     => 'Inspection Acceptance',
            'next_due'      => $dr_date->addDays(2),
            'last_date'     => $transaction_date,
            'status'        => 'Inspection Started',
            'delay_count'   => $day_delayed,
            'calendar_days' => $cd + $result->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr_id);


        event(new Event($upr_result, $upr_result->ref_number." Inspection Started"));

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
        InspectionAndAcceptanceRequest $request,
        DeliveryOrderRepository $delivery,
        InspectionAndAcceptanceRepository $model)
    {
        $dr_id                      =   $request->dr_id;
        $inputs                     =   $request->getData();
        $dr_model                   =   $delivery->update(['status' => 'ongoing inspection'], $dr_id);
        $items                      =   $request->only(['invoice_number', 'invoice_date']);

        $inputs['rfq_id']           =   $dr_model->rfq_id;
        $inputs['upr_id']           =   $dr_model->upr_id;
        $inputs['rfq_number']       =   $dr_model->rfq_number;
        $inputs['upr_number']       =   $dr_model->upr_number;
        $inputs['delivery_number']  =   $dr_model->delivery_number;
        $inputs['prepared_by']      =   \Sentinel::getUser()->id;
        $inputs['status']           =   'ongoing';

        $result = $model->save($inputs);
        if($result)
        {
            $invoices                   =   [];
            for ($i=0; $i < count($items['invoice_number']); $i++) {
                $invoices[]  =   [
                    'invoice_number'    =>  $items['invoice_number'][$i],
                    'invoice_date'      =>  $items['invoice_date'][$i],
                    'acceptance_id'     =>  $result->id,
                ];
            }

            DB::table('inspection_acceptance_invoices')->insert($invoices);
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
        RFQProponentRepository $proponents,
        InspectionAndAcceptanceRepository $model,
        NOARepository $noa,
        SignatoryRepository $signatories)
    {
        $result             =   $model->with('invoices')->findById($id);


        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $supplier           =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        }


        $signatory_list     =   $signatories->lists('id','name');

        return $this->view('modules.procurements.inspection-acceptance.show',[
            'data'          =>  $result,
            'signatory_list'=>  $signatory_list,
            'supplier'      =>  $supplier,
            'indexRoute'    =>  $this->baseUrl.'index',
            'printRoute'    =>  $this->baseUrl.'print',
            'editRoute'     =>  $this->baseUrl.'edit',
            'acceptRoute'   =>  $this->baseUrl.'accepted',
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update-signatory', $id],
                    'method'    =>  'PUT'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb($result->upr_number, 'procurements.unit-purchase-requests.show', $result->upr_id),
                new Breadcrumb('TIAC', 'procurements.inspection-and-acceptance.index')
            ]
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, InspectionAndAcceptanceRepository $model, SignatoryRepository $signatories)
    {
        $result =   $model->findById($id);
        $signatory_list     =   $signatories->lists('id','name');

        return $this->view('modules.procurements.inspection-acceptance.edit',[
            'data'          =>  $result,
            'signatory_list'=>  $signatory_list,
            'indexRoute'    =>  $this->baseUrl.'show',
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update', $id],
                    'method'    =>  'PUT',
                    'novalidate'=>  'novalidate'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('TIAC', 'procurements.inspection-and-acceptance.show',$result->id),
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
        InspectionAndAcceptanceRepository $model)
    {
        $iar_model  =   $model->findById($id);

        $this->validate($request, [
            "update_remarks"        => 'required',
            "inspection_date"       => 'required',
            'acceptance_signatory'  =>  'required',
            'inspection_signatory'  =>  'required',
            'sao_signatory'         =>  'required',
        ]);

        $input  =[
            "update_remarks"        => $request->update_remarks,
            "accepted_date"         => $request->accepted_date,
            "inspection_date"       => $request->inspection_date,
            "acceptance_signatory"  => $request->acceptance_signatory,
            "inspection_signatory"  => $request->inspection_signatory,
            "sao_signatory"         => $request->sao_signatory,
        ];

        if($iar_model->acceptance_signatory != $request->acceptance_signatory)
        {
            $acceptor  =   $signatories->findById($request->acceptance_signatory);
            $input['acceptance_name_signatory']   =   $acceptor->name."/".$acceptor->ranks."/".$acceptor->branch."/".$acceptor->designation;
        }

        if($iar_model->inspection_signatory != $request->inspection_signatory)
        {
            $inspector  =   $signatories->findById($request->inspection_signatory);
            $input['inspection_name_signatory']   =   $inspector->name."/".$inspector->ranks."/".$inspector->branch."/".$inspector->designation;
        }


        if($iar_model->sao_signatory != $request->sao_signatory)
        {
            $sao  =   $signatories->findById($request->sao_signatory);
            $input['sao_name_signatory']   =   $sao->name."/".$sao->ranks."/".$sao->branch."/".$sao->designation;
        }

        $result     =   $model->update($input, $id);

        $dr_model               =   $result->delivery;

        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('inspection_date') );
        $dr_date                =   Carbon::createFromFormat('!Y-m-d', $dr_model->date_delivered_to_coa );
        $cd                     =   $dr_date->diffInDays($transaction_date);

        $day_delayed            =   $dr_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed > 2)
        {
            $day_delayed = $day_delayed - 2;
        }

        if($wd != $result->days)
        {
            $model->update(['days' => $wd], $id);
        }

        $modelType  =   'Revlv\Procurements\InspectionAndAcceptance\InspectionAndAcceptanceEloquent';
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
    public function destroy($id, InspectionAndAcceptanceRepository $model)
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
        InspectionAndAcceptanceRepository $model,
        DeliveryOrderRepository $delivery,
        NOARepository $noa,
        HeaderRepository $headers
        )
    {
        $model                      =  $model->with(['acceptor', 'inspector'])->findById($id);
        if($model->acceptor ==null || $model->inspector == null)
        {
            return redirect()->back()->with(['error' => 'please add signatory']);
        }

        $result                     =  $delivery->with(['upr', 'po'])->findById($model->dr_id);

        // $noa_model                  =   $noa->with('winner')->findByRFQ($result->rfq_id)->winner->supplier;


        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $noa_model                  =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $noa_model                  =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        }

        $header                     =  $headers->findByUnit($result->upr->units);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $data['po_number']          =  $result->po->po_number;
        $data['header']             =  $result->upr->centers;
        $data['purchase_date']      =  $result->po->purchase_date;
        $data['bid_amount']         =  $result->po->bid_amount;
        $data['project_name']       =  $result->upr->project_name;
        $data['center']             =  $result->upr->centers->name;
        $data['signatory']          =  $result->signatory;
        $data['winner']             =  $noa_model->name;
        $data['expected_date']      =  $result->expected_date;
        $data['items']              =  $result->po->items;
        $data['accepted_date']      =  $model->accepted_date;
        $data['inspection_date']    =  $model->inspection_date;
        $data['acceptor']           =  explode('/',$model->acceptance_name_signatory);
        $data['inspector']          =  explode('/',$model->inspection_name_signatory);

        $pdf = PDF::loadView('forms.iar', ['data' => $data])
            ->setOption('margin-bottom', 30)
            ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('iar.pdf');
    }

    /**
     * [viewPrintMFO description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrintMFO(
        $id,
        InspectionAndAcceptanceRepository $model,
        DeliveryOrderRepository $delivery,
        NOARepository $noa, HeaderRepository $headers
        )
    {
        $model                      =  $model->with(['acceptor', 'inspector'])->findById($id);
        if($model->acceptor ==null || $model->inspector == null)
        {
            return redirect()->back()->with(['error' => 'please add signatory']);
        }

        $result                     =  $delivery->with(['upr', 'po'])->findById($model->dr_id);

        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $noa_model                  =   $noa->with('winner')->findByUPR($result->upr_id)->biddingWinner->supplier;
        }
        else
        {
            $noa_model                  =   $noa->with('winner')->findByUPR($result->upr_id)->winner->supplier;
        }

        $header                     =  $headers->findByUnit($result->upr->units);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $data['po_number']          =  $result->po->po_number;
        $data['purchase_date']      =  $result->po->purchase_date;
        $data['bid_amount']         =  $result->po->bid_amount;
        $data['project_name']       =  $result->upr->project_name;
        $data['center']             =  $result->upr->centers->name;
        $data['signatory']          =  $result->signatory;
        $data['delivery_date']      =  $result->delivery_date;
        $data['delivery_number']    =  $result->delivery_number;
        $data['venue']              =  $result->upr->place_of_delivery;
        $data['winner']             =  $noa_model->name;
        $data['expected_date']      =  $result->expected_date;
        $data['items']              =  $result->po->items;
        $data['accepted_date']      =  $model->accepted_date;
        $data['inspection_date']    =  $model->inspection_date;
        $data['header']             =  $result->upr->centers;
        $data['acceptor']           =  $model->acceptor;
        $data['inspector']          =  $model->inspector;
        $data['sao']                =  explode('/',$model->sao_name_signatory);
        $data['invoices']          =  $model->invoices;

        $pdf = PDF::loadView('forms.mfo', ['data' => $data])
            ->setOption('margin-bottom', 30)
            ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('iar.pdf');
    }


    /**
     * [updateSignatory description]
     *
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function updateSignatory(Request $request, $id, InspectionAndAcceptanceRepository $model)
    {
        $this->validate($request, [
            'acceptance_signatory'   =>  'required',
            'inspection_signatory'   =>  'required',
            'sao_signatory'   =>  'required',
        ]);
        $model->update(['sao_signatory' =>$request->sao_signatory, 'acceptance_signatory' =>$request->acceptance_signatory, 'inspection_signatory' =>$request->inspection_signatory], $id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [viewLogs description]
     *
     * @param  [type]             $id    [description]
     * @param  BlankRFQRepository $model [description]
     * @return [type]                    [description]
     */
    public function viewLogs($id, InspectionAndAcceptanceRepository $model, AuditLogRepository $logs)
    {

        $modelType  =   'Revlv\Procurements\InspectionAndAcceptance\InspectionAndAcceptanceEloquent';
        $result     =   $logs->findByModelAndId($modelType, $id);
        $data_model =   $model->findById($id);

        return $this->view('modules.procurements.delivery.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'model'         =>  $data_model,
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('TIAC', 'procurements.inspection-and-acceptance.show',$data_model->id),
                new Breadcrumb('Logs'),
            ]
        ]);
    }
}
