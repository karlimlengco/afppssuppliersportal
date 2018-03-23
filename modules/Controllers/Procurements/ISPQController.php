<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use DB;
use Carbon\Carbon;
use \App\Support\Breadcrumb;
use App\Events\Event;

use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Procurements\InvitationToSubmitQuotation\ISPQRepository;
use \Revlv\Procurements\InvitationToSubmitQuotation\ISPQRequest;
use \Revlv\Procurements\InvitationToSubmitQuotation\UpdateRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\InvitationToSubmitQuotation\Quotations\QuotationRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Users\Logs\UserLogRepository;
use \Revlv\Users\UserRepository;
use Validator;
use \Revlv\Settings\Forms\Header\HeaderRepository;
use \Revlv\Settings\Forms\PCCOHeader\PCCOHeaderRepository;
use \Revlv\Settings\Holidays\HolidayRepository;

class ISPQController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "procurements.ispq.";

    /**
     * [$upr description]
     *
     * @var [type]
     */
    protected $upr;
    protected $rfq;
    protected $signatories;
    protected $quotations;
    protected $audits;
    protected $holidays;
    protected $users;
    protected $userLogs;
    protected $headers;
    protected $pccoHeaders;

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
    public function getDatatable(ISPQRepository $model)
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
        return $this->view('modules.procurements.ispq.index',[
            'createRoute'   =>  $this->baseUrl."create",
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('Invitation to Submit Price Quotation'),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(
        UnitPurchaseRequestRepository $upr,
        SignatoryRepository $signatories,
        BlankRFQRepository $rfq)
    {
        $rfq_list           =   $rfq->lists('id', 'rfq_number');
        $signatory_lists    =   $signatories->lists('id', 'name');
        $this->view('modules.procurements.ispq.create',[
            'indexRoute'        =>  $this->baseUrl.'index',
            'rfq_list'          =>  $rfq_list,
            'signatory_lists'   =>  $signatory_lists,
            'modelConfig'       =>  [
                'store' =>  [
                    'route'     =>  $this->baseUrl.'store'
                ]
            ]
        ]);
    }

    public function createByRFQ(
        $id,
        QuotationRepository $quotations,
        Request $request,
        ISPQRepository $model,
        UnitPurchaseRequestRepository $upr,
        SignatoryRepository $signatories,
        BlankRFQRepository $rfq,
        HolidayRepository $holidays
        )
    {
        $this->validate($request, [
            'venue'                     =>  'required',
            'signatory_id'              =>  'required',
            'canvassing_date'           =>  'required',
            'canvassing_time'           =>  'required',
            'transaction_dates'         =>  'required',
        ]);

        $upr_model              =   $upr->findById($id);
        // $rfq_model              =   $upr_model->rfq;

        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('transaction_dates') );

        $holiday_lists          =   $holidays->lists('id','holiday_date');
        $cd                     =   $upr_model->date_processed->diffInDays($transaction_date);

        $day_delayed            =   $upr_model->date_processed->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed  > 3)
        {
            $day_delayed            =   $day_delayed - 3;
        }


        // Validate Remarks when  delay
        $validator = Validator::make($request->all(),[
            'canvassing_date'  =>  'required|after_or_equal:'.$upr_model->date_processed,
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('remarks') == null && $day_delayed > 3) {
                $validator->errors()->add('remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('action') == null && $day_delayed > 3) {
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


        $signatory  =   $signatories->findById($request->signatory_id);
        $signatory_text  =   $signatory->name."/".$signatory->ranks."/".$signatory->branch."/".$signatory->designation;


        // Validate Remarks when  delay
        $result =   $model->save([
            'prepared_by'       =>  \Sentinel::getUser()->id,
            'canvassing_date'   =>  $request->get('canvassing_date'),
            'canvassing_time'   =>  $request->get('canvassing_time'),
            'venue'             =>  $request->get('venue'),
            'signatory_id'      =>  $request->get('signatory_id'),
            'transaction_date'  =>  $request->get('transaction_dates'),
            'signatory_text'  =>  $signatory_text,
        ]);

        $data           =   [
            'ispq_id'           =>  $result->id,
            'rfq_id'            =>  $upr_model->id,
            'upr_id'            =>  $upr_model->id,
            'description'       =>  $upr_model->project_name,
            'total_amount'      =>  $upr_model->total_amount,
            'upr_number'        =>  $upr_model->ref_number,
            'rfq_number'        =>  $upr_model->ref_number,
            'delay_count'       =>  $wd,
            'canvassing_date'   =>  $request->get('canvassing_date'),
            'canvassing_time'   =>  $request->get('canvassing_time'),
            'remarks'           =>  $request->get('remarks'),
            'action'            =>  $request->get('action'),
        ];

        $upr_result =   $upr->update(['status' => 'ITSPQ Prepared',
            'next_allowable'=> 3,
            'next_step'     => 'PhilGeps Posting',
            'next_due'      => $upr_model->date_processed->addDays(3),
            'last_date'     => $transaction_date,
            'delay_count'   => $wd,
            'calendar_days' => $cd + $upr_model->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $upr_model->id);

        event(new Event($upr_result, $upr_result->upr_number." ITSPQ Prepared"));

        $quotations->save($data);

        return redirect()->route('procurements.unit-purchase-requests.show', $upr_model->id)->with([
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
        QuotationRepository $quotations,
        ISPQRequest $request,
        ISPQRepository $model,
        BlankRFQRepository $rfq)
    {
        $result =   $model->save([
            'prepared_by'       =>  \Sentinel::getUser()->id,
            'venue'             =>  $request->get('venue'),
            'signatory_id'      =>  $request->get('signatory_id'),
            'transaction_date'  =>  $request->get('transaction_date'),
            'canvassing_date'   =>  $request->get('canvassing_date'),
            'canvassing_time'   =>  $request->get('canvassing_time'),
        ]);
        $items  =   $request->get('items');
        foreach($items as $key => $item)
        {
            $newId          =   $items[$key];
            $rfq_model      =   $rfq->getById($newId);
            $data           =   [
                'ispq_id'           =>  $result->id,
                'rfq_id'            =>  $rfq_model->id,
                'upr_id'            =>  $rfq_model->upr_id,
                'description'       =>  $rfq_model->upr->project_name,
                'total_amount'      =>  $rfq_model->upr->total_amount,
                'upr_number'        =>  $rfq_model->upr_number,
                'rfq_number'        =>  $rfq_model->rfq_number,
                'canvassing_date'   =>  $request->get('canvassing_date'),
                'canvassing_time'   =>  $request->get('canvassing_time'),
            ];

            $quotations->save($data);
        }

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     * [viewPrint description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrint($id, ISPQRepository $model, HeaderRepository $headers, PCCOHeaderRepository $pccoHeaders)
    {
        $result     =   $model->with(['quotations'])->findById($id);
        $center     =   $result->quotations->first()->upr->centers;
        $header                     =  $pccoHeaders->findByPCCO($result->quotations->first()->upr->procurement_office);
        $data['unitHeader']         =  (isset($header)) ? $header->content : "" ;
        $data['transaction_date']   =  $result->transaction_date;
        $data['venue']              =  $result->venue;
        $data['signatories']        =  explode('/', $result->signatory_text);
        if($result->signatory_text == null){
          $data['signatories'] = $result->signatories->name."/".$result->signatories->ranks."/".$result->signatories->branch."/".$result->signatories->designation;
          $data['signatories']        =  explode('/', $data['signatories']);
        }
        $data['quotations']         =  $result->quotations;
        $pdf = PDF::loadView('forms.ispq', ['data' => $data, 'center' => $center])
            ->setOption('margin-bottom', 30)
            ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('ispq.pdf');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(
        $id,
        ISPQRepository $model,
        SignatoryRepository $signatories,
        BlankRFQRepository $rfq)
    {
        $result             =   $model->findById($id);
        $signatory_lists    =   $signatories->lists('id', 'name');

        return $this->view('modules.procurements.ispq.edit',[
            'data'              =>  $result,
            'signatory_lists'   =>  $signatory_lists,
            'indexRoute'        =>  $this->baseUrl.'index',
            'modelConfig'       =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update', $id],
                    'method'    =>  'PUT',
                    'novalidate'=> 'novalidate'
                ],
                'destroy'   => [
                    'route' => [$this->baseUrl.'destroy',$id],
                    'method'=> 'DELETE'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('Invitation to Submit Price Quotation', 'procurements.ispq.index'),
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
        UpdateRequest $request,
        $id,
        UnitPurchaseRequestRepository $upr,
        \Revlv\Users\UserRepository $users,
        AuditLogRepository $audits,
        HolidayRepository $holidays,
        UserLogRepository $userLogs,
        SignatoryRepository $signatories,
        QuotationRepository $quotations,
        ISPQRepository $model
        )
    {
        $this->validate($request, [
            'canvassing_date' =>  'required',
            'canvassing_time' =>  'required'
        ]);
        $ispq   =   $model->findById($id);

        $inputs =   $request->getData();
        if($ispq->signatory_id != $request->signatory_id)
        {

            $signatory  =   $signatories->findById($request->signatory_id);
            $inputs['signatory_text']  =   $signatory->name."/".$signatory->ranks."/".$signatory->branch."/".$signatory->designation;
        }

        $result =   $model->update($inputs, $id);

        foreach($result->quotations as $quote)
        {
            $upr_model              =   $upr->findById($quote->upr_id);
            $rfq_model              =   $upr_model->rfq;
            $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('transaction_date') );

            $holiday_lists          =   $holidays->lists('id','holiday_date');
            $cd                     =   $upr_model->date_processed->diffInDays($transaction_date);

            $day_delayed            =   $upr_model->date_processed->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
                return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
            }, $transaction_date);

            $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

            if($day_delayed  > 3)
            {
                $day_delayed            =   $day_delayed - 3;
            }
            $quotations->update(['canvassing_date' => $request->canvassing_date, 'canvassing_time' => $request->canvassing_time], $quote->id);
            if($wd != $result->days)
            {
                $model->update(['days' => $wd, ], $id);
            }
        }

        $modelType  =   'Revlv\Procurements\InvitationToSubmitQuotation\ISPQEloquent';
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

        return redirect()->route($this->baseUrl.'edit', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, ISPQRepository $model)
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
    public function viewLogs($id, ISPQRepository $model, AuditLogRepository $logs)
    {

        $modelType  =   'Revlv\Procurements\InvitationToSubmitQuotation\ISPQEloquent';
        $result     =   $logs->findByModelAndId($modelType, $id);
        $data_model =   $model->findById($id);

        return $this->view('modules.procurements.ispq.logs',[
            'indexRoute'    =>  $this->baseUrl."edit",
            'data'          =>  $result,
            'model'         =>  $data_model,
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('Invitation to Submit Price Quotation', 'procurements.ispq.edit', $data_model->id),
                new Breadcrumb('Logs'),
            ]
        ]);
    }
}
