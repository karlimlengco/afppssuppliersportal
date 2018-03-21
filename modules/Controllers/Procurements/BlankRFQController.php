<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;
use Carbon\Carbon;
use \App\Support\Breadcrumb;
use App\Events\Event;

use \Revlv\Settings\Forms\Header\HeaderRepository;
use \Revlv\Settings\Forms\PCCOHeader\PCCOHeaderRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Procurements\BlankRequestForQuotation\UpdateRequest;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\Suppliers\SupplierRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Users\Logs\UserLogRepository;
use \Revlv\Settings\Holidays\HolidayRepository;
use \Revlv\Settings\Forms\RFQ\RFQRepository;
use Validator;

class BlankRFQController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "procurements.blank-rfq.";

    /**
     * [$upr description]
     *
     * @var [type]
     */
    protected $upr;
    protected $rfqForms;
    protected $suppliers;
    protected $signatories;
    protected $audits;
    protected $holidays;
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
    public function getDatatable(BlankRFQRepository $model)
    {
        return $model->getDatatable();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, BlankRFQRepository $rfq)
    {
        return $this->view('modules.procurements.blank-rfq.index',[
            'createRoute'   =>  $this->baseUrl."create",
            'resources'     =>  $rfq->paginateByRequest(10, $request),
            'breadcrumbs' => [
                new Breadcrumb('Alternative', 'procurements.blank-rfq.index'),
                new Breadcrumb('Request For Quotations'),
            ]
        ]);
    }

    /**
     * [getInfo description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getInfo($id, BlankRFQRepository $rfq, UnitPurchaseRequestRepository $upr)
    {
        $result =   $upr->getInfo($id);

        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(UnitPurchaseRequestRepository $upr)
    {
        $upr_list   =   $upr->listPending('id', 'upr_number');
        $this->view('modules.procurements.blank-rfq.create',[
            'indexRoute'    =>  $this->baseUrl.'index',
            'upr_list'      =>  $upr_list,
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
        BlankRFQRequest $request,
        BlankRFQRepository $model,
        UnitPurchaseRequestRepository $upr,
        HolidayRepository $holidays)
    {

        // dd($request->getData());
        $upr_model              =   $upr->findById($request->upr_id);
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->transaction_date);
        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $day_delayed            =   $upr_model->date_processed->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $cd                     =   $upr_model->date_processed->diffInDays($transaction_date);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed > 3)
        {
            $day_delayed = $day_delayed - 3;
        }
        // Validate Remarks when  delay
        $validator = Validator::make($request->all(),[
            'transaction_date'  =>  'required|after_or_equal:'.$upr_model->date_processed,
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('remarks') == null && $day_delayed > 3) {
                $validator->errors()->add('remarks', 'This field is required when your process is delay');
            }

            if( $request->get('action') == null && $day_delayed > 3)
            {
                $validator->errors()->add('action', 'This field is required');
            }

        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Please Check Your Fields.'])
                        ->withErrors($validator)
                        ->withInput();
        }
        // Validate Remarks when  delay

        $inputs                 =   $request->getData();
        $inputs['upr_number']   =   $upr_model->upr_number;
        $inputs['processed_by'] =   \Sentinel::getUser()->id;
        $split_upr              =   explode('-', $upr_model->ref_number);
        $inputs['rfq_number']   =   "RFQ-".$split_upr[1]."-".$split_upr[2]."-".$split_upr[3]."-".$split_upr[4] ;

        $inputs['days']         =   $wd;

        $result = $model->save($inputs);

        $upr_result =   $upr->update([
            'status'        => 'Processing RFQ',
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $upr_model->id);


        event(new Event($upr_result, $upr_result->ref_number." Process RFQ"));

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
        SignatoryRepository $signatories,
        BlankRFQRepository $model,
        SupplierRepository $suppliers)
    {
        $supplier_lists =   $suppliers->lists('id', 'name');
        $signatory_lists=   $signatories->lists('id', 'name');
        $result         =   $model->with(['invitations', 'proponents','upr', 'canvassing'])->findById($id);

        $exist_supplier =   $result->proponents->pluck('proponents')->toArray();

        foreach($exist_supplier as $list)
        {
            unset($supplier_lists[$list]);
        }

        return $this->view('modules.procurements.blank-rfq.show',[
            'supplier_lists'    =>  $supplier_lists,
            'data'              =>  $result,
            'signatory_lists'   =>  $signatory_lists,
            'indexRoute'        =>  $this->baseUrl.'index',
            'printRoute'        =>  $this->baseUrl.'print',
            'editRoute'         =>  $this->baseUrl.'edit',
            'modelConfig'       =>  [
                'add_proponents'   => [
                    'route' => 'procurements.rfq-proponents.store',
                    'method'=> 'DELETE'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb($result->upr_number, 'procurements.unit-purchase-requests.show', $result->upr_id ),
                new Breadcrumb($result->rfq_number),
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
        SignatoryRepository $signatories,
        BlankRFQRepository $model,
        UnitPurchaseRequestRepository $upr)
    {
        $result             =   $model->findById($id);
        $upr_list           =   $upr->lists('id', 'upr_number');
        $signatory_list     =   $signatories->lists('id','name');

        return $this->view('modules.procurements.blank-rfq.edit',[
            'data'          =>  $result,
            'signatory_list'=>  $signatory_list,
            'upr_list'      =>  $upr_list,
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
                new Breadcrumb('Alternative', 'procurements.blank-rfq.index'),
                new Breadcrumb($result->rfq_number, 'procurements.blank-rfq.show', $result->id),
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
        UpdateRequest $request,
        $id,
        UnitPurchaseRequestRepository $upr,
        \Revlv\Users\UserRepository $users,
        AuditLogRepository $audits,
        HolidayRepository $holidays,
        SignatoryRepository $signatories,
        UserLogRepository $userLogs,
        BlankRFQRepository $model)
    {
        $old                    =   $model->findById($id);
        $inputs                 =   $request->getData();
        if($old->chief != $request->chief)
        {

            $requestor  =   $signatories->findById($request->chief);
            $inputs['signatory_chief']   =   $requestor->name."/".$requestor->ranks."/".$requestor->branch."/".$requestor->designation;
        }

        $result                 =   $model->update($inputs, $id);

        $upr_model              =   $upr->findById($result->upr_id);


        if($request->completed_at != null)
        {

            $completed_at       =   createCarbon('Y-m-d',$request->completed_at);

            $ispq_transaction_date   = $upr_model->date_processed;

            $holiday_lists      =   $holidays->lists('id','holiday_date');
            $day_delayed        =   $ispq_transaction_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
                return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
            }, $completed_at);

            $cd                     =   $ispq_transaction_date->diffInDays($completed_at);
            $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

            if($day_delayed >= 3)
            {
                $day_delayed            =   $day_delayed - 3;
            }

            if($wd != $result->days)
            {
                $model->update(['days' => $wd], $id);
            }
        }

        $modelType  =   'Revlv\Procurements\BlankRequestForQuotation\BlankRFQEloquent';
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

        if($request->completed_at != null)
        {
            $upr_result =   $upr->update([
                'status'        => 'RFQ',
                'next_allowable'=> 2,
                'next_step'     => 'Canvassing',
                'next_due'      => $completed_at->addDays(2),
                'last_date'     => $completed_at,
                ], $upr_model->id);
        }


        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * [closed description]
     *
     * @param  [type]             $id    [description]
     * @param  BlankRFQRepository $model [description]
     * @return [type]                    [description]
     */
    public function closed(
        Request $request,
        BlankRFQRepository $model,
        UnitPurchaseRequestRepository $upr,
        HolidayRepository $holidays)
    {
        $rfq                =   $model->findById($request->rfq_id);

        if(count($rfq->proponents) < 1)
        {
            return redirect()->back()->with(['error' => 'No Proponents Added. to add proponents click options and add proponent']);
        }

        $completed_at       =   createCarbon('Y-m-d',$request->completed_at);

        $ispq_transaction_date   = $rfq->upr->date_processed;

        $holiday_lists      =   $holidays->lists('id','holiday_date');
        $day_delayed        =   $ispq_transaction_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $completed_at);

        $cd                     =   $ispq_transaction_date->diffInDays($completed_at);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed >= 3)
        {
            $day_delayed            =   $day_delayed - 3;
        }

        // Validate Remarks when  delay
        $validator = Validator::make($request->all(),[
            'completed_at'      =>  'required|after_or_equal:'. $ispq_transaction_date->format('Y-m-d'),
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('close_remarks') == null && $day_delayed >= 3 ) {
                $validator->errors()->add('close_remarks', 'This field is required when your process is delay');
            }
            if ( $request->get('close_action') == null && $day_delayed >= 3 ) {
                $validator->errors()->add('close_action', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Please Check Your Fields.'])
                        ->withErrors($validator)
                        ->withInput();
        }

        if(count($rfq->proponents) == 0)
        {
            return redirect()->route($this->baseUrl.'show', $request->rfq_id)->with([
                'error'  => "RFQ cannot be close without proponents"
            ]);
        }

        $model->update([
            'status'        => 'closed',
            'completed_at'  => $request->completed_at,
            'close_remarks' => $request->close_remarks,
            'close_action'  => $request->close_action,
            'close_days'    => $wd,
            ], $request->rfq_id);

        $upr_result =   $upr->update([
            'status'        => 'RFQ',
            'next_allowable'=> 2,
            'next_step'     => 'Canvassing',
            'next_due'      => $completed_at->addDays(2),
            'last_date'     => $completed_at,
            'calendar_days' => $cd + $rfq->upr->calendar_days,
            'last_action'   => $request->close_remarks,
            'last_remarks'  => $request->close_action
            ], $rfq->upr_id);


        event(new Event($upr_result, $upr_result->ref_number." RFQ"));

        return redirect()->route($this->baseUrl.'show', $request->rfq_id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, BlankRFQRepository $model)
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
    public function viewPrint($id, BlankRFQRepository $model, RFQRepository $rfqForms, HeaderRepository $headers, PCCOHeaderRepository $pccoHeaders)
    {
        $result     =   $model->with(['upr'])->findById($id);

        $header                     =  $pccoHeaders->findByPCCO($result->upr->procurement_office);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;

        $data['chief']              =  explode('/', $result->signatory_chief);
        $data['items']              =  $result->upr->items;
        $data['total_amount']       =  $result->upr->total_amount;
        $data['place']              =  $result->upr->place_of_delivery;
        $data['header']             =  $result->upr->centers;
        $rfqFormsContent            =   $rfqForms->findByPCCO($result->upr->centers->id);
        $data['content']            =  ($rfqFormsContent) ? $rfqFormsContent->content : "";
        $data['transaction_date']   =  $result->transaction_date;
        $data['rfq_number']         =  $result->rfq_number;
        if($result->upr->philgeps)
        {

            $data['deadline']           =  \Carbon\Carbon::createFromFormat( 'Y-m-d H:i', $result->upr->philgeps->deadline_rfq." ".$result->upr->philgeps->opening_time);
        }
        else
        {
            $data['deadline']           = \Carbon\Carbon::createFromFormat( 'Y-m-d H:i:s', $result->upr->invitations->canvassing_date ." ". $result->upr->invitations->canvassing_time);
        }
        $data['items']              =  $result->upr->items;
        $pdf = PDF::loadView('forms.rfq', ['data' => $data])
            ->setOption('margin-bottom', 30)
            ->setOption('footer-html', route('pdf.footer'));

        return $pdf
            ->setOption('page-width', '8.5in')
            ->setOption('page-height', '14in')
            ->inline('rfq.pdf');
    }

    /**
     * [viewLogs description]
     *
     * @param  [type]             $id    [description]
     * @param  BlankRFQRepository $model [description]
     * @return [type]                    [description]
     */
    public function viewLogs($id, BlankRFQRepository $model, AuditLogRepository $logs)
    {

        $modelType  =   'Revlv\Procurements\BlankRequestForQuotation\BlankRFQEloquent';
        $result     =   $logs->findByModelAndId($modelType, $id);
        $rfq_model  =   $model->findById($id);

        return $this->view('modules.procurements.blank-rfq.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'rfq'           =>  $rfq_model,
        ]);
    }
}
