<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use Carbon\Carbon;
use \App\Support\Breadcrumb;
use Validator;
use App\Events\Event;

use \Revlv\Procurements\NoticeOfAward\NOARepository;
use \Revlv\Procurements\NoticeToProceed\NTPRepository;
use \Revlv\Procurements\PurchaseOrder\PORepository;
use \Revlv\Procurements\Canvassing\CanvassingRepository;
use \Revlv\Procurements\Canvassing\CanvassingRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\RFQProponents\RFQProponentRepository;
use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\Holidays\HolidayRepository;
use \Revlv\Users\Logs\UserLogRepository;
use \Revlv\Users\UserRepository;

class NoticeToProceedController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "procurements.ntp.";

    /**
     * [$blank description]
     *
     * @var [type]
     */
    protected $blank;
    protected $upr;
    protected $rfq;
    protected $po;
    protected $ntp;
    protected $noa;
    protected $signatories;
    protected $audits;
    protected $holidays;
    protected $users;
    protected $userLogs;

    /**
     * [$model description]
     *
     * @var [type]p
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
    public function getDatatable(NTPRepository $model)
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
        return $this->view('modules.procurements.ntp.index',[
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('Notice to Proceed', 'procurements.purchase-orders.index')
            ]
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
        NTPRepository $model,
        PORepository $po,
        SignatoryRepository $signatories,
        RFQProponentRepository $proponents,
        UnitPurchaseRequestRepository $upr)
    {
        $result             =   $model->with(['winner'])->findById($id);
        $po_model           =   $po->with(['items'])->findById($result->po_id);

        if($result->upr->mode_of_procurement == 'public_bidding')
        {
            $supplier           =   $result->biddingWinner->supplier;
        }
        else
        {
            $supplier           =   $result->winner->supplier;
        }

        $upr_model          =   $upr->with(['centers','modes','unit','charges','accounts','terms','users'])->findById($result->rfq_id);

        $signatory_list     =   $signatories->lists('id','name');

        return $this->view('modules.procurements.ntp.show',[
            'data'          =>  $result,
            'upr_model'     =>  $upr_model,
            'supplier'      =>  $supplier,
            'po_model'      =>  $po_model,
            'signatory_list'=>  $signatory_list,
            'indexRoute'    =>  $this->baseUrl.'index',
            'editRoute'    =>  $this->baseUrl.'edit',
            'printRoute'    =>  $this->baseUrl.'print',
            'modelConfig'   =>  [
                'receive_ntp' =>  [
                    'route'     =>  [$this->baseUrl.'update', $id],
                    'method'    =>  'PUT'
                ],
                'create_nod' =>  [
                    'route'     =>  ['procurements.delivery-orders.create-purchase', $result->po_id]
                ],
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update-signatory', $id],
                    'method'    =>  'PUT'
                ],
            ],
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb($result->upr_number, 'procurements.unit-purchase-requests.show', $result->upr_id),
                new Breadcrumb('Notice to Proceed', 'procurements.ntp.index')
            ]

        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,
        NTPRepository $model,
        NOARepository $noa,
        UnitPurchaseRequestRepository $upr,
        PORepository $po,
        HolidayRepository $holidays)
    {
        $po_model           =   $po->findById($request->po_id);
        $noa_model          =   $noa->findByUPR($po_model->upr_id);

        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('preparared_date') );
        $po_date                =   Carbon::createFromFormat('Y-m-d', $po_model->coa_approved_date );
        $cd                     =   $po_date->diffInDays($transaction_date);

        $day_delayed            =   $po_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed > 1 ){
            $day_delayed = $day_delayed - 1;
        }

        $validator = Validator::make($request->all(),[
            'preparared_date'   => 'required',
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

        $inputs             =   [
            'po_id'             =>  $request->po_id,
            'prepared_by'       =>  \Sentinel::getUser()->id,
            'prepared_date'     =>  $request->preparared_date,
            'upr_id'            =>  $po_model->upr_id,
            'rfq_id'            =>  $po_model->rfq_id,
            'rfq_number'        =>  $po_model->rfq_number,
            'upr_number'        =>  $po_model->upr_number,
            'proponent_id'      =>  $noa_model->proponent_id,
            'status'            =>  'pending',
            'days'              =>  $wd,
            'remarks'           =>  $request->remarks,
            'action'           =>  $request->action
        ];


        $upr_result =  $upr->update([
            'next_allowable'=> 1,
            'next_step'     => 'Receive NTP',
            'next_due'      => $transaction_date->addDays(1),
            'last_date'     => $transaction_date,
            'status'        => "NTP Created",
            'delay_count'   => $day_delayed,
            'calendar_days' => $cd + $po_model->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $po_model->upr_id);

        event(new Event($upr_result, $upr_result->ref_number." NTP Created"));

        $result = $model->save($inputs);

        return redirect()->route($this->baseUrl.'show', $result->id)->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function edit($id, NTPRepository $model)
    {

        $data   =   $model->findById($id);

        return $this->view('modules.procurements.ntp.edit',[
            'data'          =>  $data,
            'indexRoute'    =>  $this->baseUrl.'show',
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update-dates', $id],
                    'method'    =>  'PUT',
                    'novalidate'=>  'novalidate'
                ],
            ],
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('Notice to Proceed', 'procurements.ntp.show', $data->id),
                new Breadcrumb('Update')
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
    public function updateSignatory(Request $request, $id, NTPRepository $model)
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
     * [updateDates description]
     *
     * @param  [type]        $id      [description]
     * @param  Request       $request [description]
     * @param  NTPRepository $model   [description]
     * @return [type]                 [description]
     */
    public function updateDates(
        $id,
        Request $request,
        NTPRepository $model,
        UnitPurchaseRequestRepository $upr,
        AuditLogRepository $audits,
        HolidayRepository $holidays,
        PORepository $po,
        NOARepository $noa,
        UserLogRepository $userLogs,
        UserRepository $users)
    {
        $this->validate($request, [
            'prepared_date'         =>  'required',
            'update_remarks'        =>  'required',
        ]);

        $input  =   [
            'prepared_date'         =>  $request->prepared_date,
            'update_remarks'        =>  $request->update_remarks,
            'award_accepted_date'   =>  $request->award_accepted_date,
        ];

        $result                 =   $model->update($input, $id);

        $po_model               =   $po->findById($result->po_id);
        $noa_model              =   $noa->findByUPR($po_model->upr_id);

        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('prepared_date') );
        $po_date                =   Carbon::createFromFormat('Y-m-d', $po_model->coa_approved_date );

        $day_delayed            =   $po_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        if($day_delayed != $result->days)
        {
            $model->update(['days' => $day_delayed], $id);
        }

        $modelType  =   'Revlv\Procurements\NoticeToProceed\NTPEloquent';
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
        Request $request,
        $id,
        RFQProponentRepository $rfq,
        BlankRFQRepository $blank,
        UnitPurchaseRequestRepository $upr,
        NTPRepository $model,
        HolidayRepository $holidays
        )
    {

        $ntp_model              =   $model->findById($id);

        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('award_accepted_date') );
        $po_date                =   Carbon::createFromFormat('Y-m-d H:i:s', $ntp_model->prepared_date );
        $po_date                =   Carbon::createFromFormat('Y-m-d', $po_date->format('Y-m-d') );
        $cd                     =   $po_date->diffInDays($transaction_date);

        $day_delayed            =   $po_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;
        if($day_delayed > 1 ){
            $day_delayed = $day_delayed - 1;
        }

        $validator = Validator::make($request->all(),[
            'received_by'           =>  'required',
            'award_accepted_date'   =>  'required',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('accepted_remarks') == null && $day_delayed > 1) {
                $validator->errors()->add('accepted_remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('accepted_action') == null && $day_delayed > 1) {
                $validator->errors()->add('accepted_action', 'This field is required when your process is delay');
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

        $input  =   [
            'received_by'           =>  $request->received_by,
            'award_accepted_date'   =>  $request->award_accepted_date,
            'status'                =>  "Accepted",
            'accepted_remarks'      =>  $request->accepted_remarks,
            'accepted_action'       =>  $request->accepted_action,
            'accepted_days'         =>  $wd
        ];

        $result             =   $model->update($input, $id);

        $upr_result  =  $upr->update([
            'next_allowable'=> 1,
            'next_step'     => 'Prepare NOD',
            'next_due'      => $transaction_date->addDays(1),
            'last_date'     => $transaction_date,
            'status'        => 'NTP Accepted',
            'delay_count'   => $day_delayed,
            'calendar_days' => $cd + $ntp_model->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $result->upr_id);

        event(new Event($upr_result, $upr_result->ref_number." NPT Accepted"));

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
    public function viewPrint(
        $id,
        NTPRepository $model,
        PORepository $po,
        UnitPurchaseRequestRepository $upr,
        BlankRFQRepository $blank,
        RFQProponentRepository $proponents)
    {
        $result                     =   $model->with(['signatory', 'po'])->findById($id);
        $supplier                   =   $result->winner->supplier;
        $blank_model                =   $blank->findById($result->rfq_id);
        $upr_model                  =   $upr->findById($result->upr_id);

        if($result->signatory == null)
        {
            return redirect()->back()->with([
                'error'  => "Please select signatory first"
            ]);
        }

        $data['transaction_date']   =   $result->prepared_date;
        $data['supplier']           =   $supplier;
        $data['po_transaction_date']=   $result->po->created_at;
        $data['po_number']          =   $result->po->po_number;
        $data['rfq_number']         =   $result->rfq_number;
        $data['rfq_date']           =   $blank_model->transaction_date;
        $data['total_amount']       =   $result->po->bid_amount;
        $data['signatory']          =   $result->signatory;
        $data['project_name']       =   $upr_model->project_name;
        $data['today']              =   \Carbon\Carbon::now()->format("d F Y");

        $pdf = PDF::loadView('forms.ntp', ['data' => $data])
            ->setOption('margin-left', 13)
            ->setOption('margin-right', 13)
            ->setOption('margin-bottom', 30)
            ->setOption('footer-html', route('pdf.footer'))
            ->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('ntp.pdf');
    }

    /**
     * [viewLogs description]
     *
     * @param  [type]             $id    [description]
     * @param  BlankRFQRepository $model [description]
     * @return [type]                    [description]
     */
    public function viewLogs($id, NTPRepository $model, AuditLogRepository $logs)
    {

        $modelType  =   'Revlv\Procurements\NoticeToProceed\NTPEloquent';
        $result     =   $logs->findByModelAndId($modelType, $id);
        $data_model =   $model->findById($id);

        return $this->view('modules.procurements.ntp.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'model'         =>  $data_model,
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('Notice to Proceed', 'procurements.ntp.show', $data_model->id),
                new Breadcrumb('Log')
            ]
        ]);
    }
}
