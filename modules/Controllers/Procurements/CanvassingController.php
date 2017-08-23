<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use \Carbon\Carbon;
use Validator;
use PDF;
use \App\Support\Breadcrumb;
use App\Events\Event;

use \Revlv\Procurements\Canvassing\CanvassingRepository;
use \Revlv\Procurements\Canvassing\Signatories\SignatoryRepository as CSignatoryRepository;
use \Revlv\Procurements\Canvassing\CanvassingRequest;
use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Procurements\RFQProponents\RFQProponentRepository;
use \Revlv\Settings\Holidays\HolidayRepository;
use \Revlv\Users\Logs\UserLogRepository;
use \Revlv\Users\UserRepository;

class CanvassingController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "procurements.canvassing.";

    /**
     * [$upr description]
     *
     * @var [type]
     */
    protected $upr;
    protected $rfq;
    protected $signatories;
    protected $mysignatories;
    protected $audits;
    protected $proponents;
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
    public function getDatatable(CanvassingRepository $model)
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
        return $this->view('modules.procurements.canvassing.index',[
            'createRoute'   =>  $this->baseUrl."create",
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('Canvassing', 'procurements.canvassing.index'),
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
        $rfq_list   =   $rfq->listNotCanvass('id', 'rfq_number');

        $this->view('modules.procurements.canvassing.create',[
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
     * [openCanvass description]
     *
     * @param  [type]                        $id    [description]
     * @param  CanvassingRepository          $model [description]
     * @param  BlankRFQRepository            $rfq   [description]
     * @param  UnitPurchaseRequestRepository $upr   [description]
     * @return [type]                               [description]
     */
    public function openCanvass(
        $id,
        Request $request,
        CanvassingRepository $model,
        BlankRFQRepository $rfq,
        UnitPurchaseRequestRepository $upr,
        CSignatoryRepository $mysignatories,
        HolidayRepository $holidays)
    {
        $upr_model              =   $upr->findById($id);
        // $rfq_model              =   $rfq->with('invitations')->findById($id);
        $rfq_model              =   $upr_model->rfq;

        // if($rfq_model->invitations == null)
        // {
        //     return redirect()->back()->with([
        //         'error'     =>  'Create Invitation First'
        //     ]);
        // }

        $transaction_date       =   Carbon::createFromFormat('Y-m-d',$request->open_canvass_date);

        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $ispq_transaction_date  =   $rfq_model->completed_at;

        $cd                     =   $ispq_transaction_date->diffInDays($transaction_date);

        $day_delayed            =   $ispq_transaction_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed >= 2)
        {
            $day_delayed            =   $day_delayed - 2;
        }

        // Validate Remarks when  delay
        $validator = Validator::make($request->all(),[
            'open_canvass_date'  =>  'required|after_or_equal:'. $ispq_transaction_date->format('Y-m-d'),
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
        $inputs['rfq_number']   =   $rfq_model->rfq_number;
        $inputs['upr_number']   =   $rfq_model->upr_number;
        $inputs['upr_id']       =   $rfq_model->upr_id;
        $inputs['days']         =   $wd;
        $inputs['rfq_id']       =   $rfq_model->id;
        $inputs['canvass_date'] =   $request->open_canvass_date;
        $inputs['remarks']      =   $request->remarks;
        $inputs['action']       =   $request->action;
        $inputs['canvass_time'] =   $request->open_canvass_time;
        $inputs['open_by']      =   \Sentinel::getUser()->id;

        $inputs['presiding_officer']     =   $request->presiding_officer;
        $inputs['chief']                =   $request->chief;
        $inputs['other_attendees']      =   $request->other_attendees;

        $result = $model->save($inputs);

        for ($i=0; $i < count($request->members); $i++) {
            $mysignatories->save([
                'signatory_id'  =>  $request->members[$i],
                'canvass_id'    =>  $result->id
            ]);
        }

        $upr_result =   $upr->update([
            'next_allowable'=> 2,
            'next_step'     => 'Prepare NOA',
            'next_due'      => $ispq_transaction_date->addDays(2),
            'last_date'     => $transaction_date,
            'status'        => "Open Canvass",
            'delay_count'   => $wd,
            'calendar_days' => $cd + $rfq_model->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $rfq_model->upr_id);

        event(new Event($upr_result, $upr_result->ref_number." Open Canvas"));

        return redirect()->route($this->baseUrl.'show', $result->id)->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     * [failedCanvass description]
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function failedCanvass(
        Request $request,
        CanvassingRepository $model,
        RFQProponentRepository $proponents,
        UnitPurchaseRequestRepository $upr)
    {

        $upr_result = $upr->update([
            'status' => "Failed Bid",
            'last_remarks'  => $request->failed_remarks
            ], $request->upr_id);

        event(new Event($upr_result, $upr_result->ref_number." Failed Bid"));

        $result =   $model->update(['is_failed'=> 1, 'date_failed'=>$request->date_failed, 'failed_remarks'=>$request->failed_remarks], $request->id);


        $proponent_list =   $proponents->findByRFQId($result->rfq_id);


        foreach($proponent_list as $props)
        {
            $proponents->update(['bid_amount' => 0, 'status' => Null], $props->id);
        }

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
    public function store(CanvassingRequest $request, CanvassingRepository $model, BlankRFQRepository $rfq)
    {
        $rfq_model              =   $rfq->findById($request->rfq_id);
        $inputs                 =   $request->getData();
        $inputs['rfq_number']   =   $rfq_model->rfq_number;
        $inputs['upr_number']   =   $rfq_model->upr_number;
        $inputs['upr_id']       =   $rfq_model->upr_id;
        $canvass_date           =   $inputs['canvass_date'];

        $rfq->update(['status' => "Canvasing ($canvass_date)"], $rfq_model->id);

        $result = $model->save($inputs);

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
        CanvassingRepository $model,
        SignatoryRepository $signatories,
        CSignatoryRepository $mysignatories,
        RFQProponentRepository $proponents)
    {
        $result         =   $model->with(['opens', 'signatories', 'winners', 'upr'])->findById($id);
        $signatory_lists=   $signatories->lists('id', 'name');
        $proponent_list =   $proponents->findByRFQId($result->rfq_id);

        $my_signtories  =   $result->signatories->pluck('signatory_id', 'signatory_id');
        $signatory_info     =   $result->signatories;

        $current_signs  =   array_intersect_key( $signatory_lists, $my_signtories->toArray()  );

        return $this->view('modules.procurements.canvassing.show',[
            'data'              =>  $result,
            'signatory_lists'   =>  $signatory_lists,
            'signatory_info'    =>  $signatory_info,
            'current_signs'     =>  $current_signs,
            'proponent_list'    =>  $proponent_list,
            'indexRoute'        =>  $this->baseUrl.'index',
            'editRoute'         =>  $this->baseUrl.'edit',
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb($result->upr_number, 'procurements.unit-purchase-requests.show', $result->upr_id),
                new Breadcrumb('Canvassing'),
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, CanvassingRepository $model,
        SignatoryRepository $signatories, BlankRFQRepository $rfq)
    {
        $result     =   $model->findById($id);
        $signatory_lists=   $signatories->lists('id', 'name');
        $rfq_list   =   $rfq->lists('id', 'rfq_number');

        return $this->view('modules.procurements.canvassing.edit',[
            'data'          =>  $result,
            'signatory_list'   =>  $signatory_lists,
            'rfq_list'      =>  $rfq_list,
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
                new Breadcrumb('Canvassing', 'procurements.canvassing.show', $result->id),
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
        CanvassingRequest $request,
        $id,
        UnitPurchaseRequestRepository $upr,
        AuditLogRepository $audits,
        HolidayRepository $holidays,
        UserLogRepository $userLogs,
        UserRepository $users,
        CanvassingRepository $model)
    {
        $inputs                         =   $request->getData();
        $inputs['presiding_officer']    =   $request->presiding_officer;
        $inputs['chief']                =   $request->chief;
        $inputs['other_attendees']      =   $request->other_attendees;

        $result                 =   $model->update($inputs, $id);

        $upr_model              =   $upr->findById($result->id);
        // $rfq_model              =   $rfq->with('invitations')->findById($id);
        $rfq_model              =   $upr_model->rfq;

        $transaction_date       =   Carbon::createFromFormat('Y-m-d',$request->canvass_date);

        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $ispq_transaction_date  =   $rfq_model->completed_at;

        $day_delayed            =   $ispq_transaction_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $cd                     =   $ispq_transaction_date->diffInDays($transaction_date);
        $wd                     =   ($day_delayed > 0) ?  $day_delayed - 1 : 0;

        if($day_delayed != 0)
        {
            $day_delayed            =   $day_delayed - 1;
        }

        if($wd != $result->days)
        {
            $model->update(['days' => $wd], $id);
        }

        $modelType  =   'Revlv\Procurements\Canvassing\CanvassingEloquent';
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
     * [addSignatories description]
     *
     */
    public function addSignatories(
        $id,
        Request $request,
        CSignatoryRepository $mysignatories,
        CanvassingRepository $model
        )
    {

        $canvass    =   $model->with('signatories')->findById($id);

        foreach($canvass->signatories as $signa)
        {
            $mysignatories->update(['is_present' => 0, 'cop' => 0, 'rop' => 0], $signa->id);
        }

        for ($i=0; $i < count($request->attendance); $i++) {
            $mysignatories->update(['is_present' => 1], $request->attendance[$i]);
        }

        $mysignatories->update(['cop' => 1], $request->cop);
        $mysignatories->update(['rop' => 1], $request->rop);

        // $mysignatories->deleteAllByCanvass($id);

        // for ($i=0; $i < count($request->signatory_id); $i++) {
        //     $mysignatories->save([
        //         'signatory_id'  =>  $request->signatory_id[$i],
        //         'canvass_id'    =>  $id
        //     ]);
        // }

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, CanvassingRepository $model)
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
    public function viewPrint($id, CanvassingRepository $model)
    {
        $result     =   $model->with(['rfq', 'upr', 'signatories'])->findById($id);
        $min = min(array_column($result->rfq->proponents->toArray(), 'bid_amount'));

        $data['date']               =  $result->canvass_date." ". $result->canvass_time;

        $data['rfq_number']         =  $result->rfq->rfq_number;
        $data['header']             =  $result->upr->centers;
        $data['total_amount']       =  $result->upr->total_amount;
        $data['unit']               =  $result->upr->unit->short_code;
        $data['center']             =  $result->upr->centers->name;
        $data['venue']              =  $result->rfq->invitations->ispq->venue;
        $data['signatories']        =  $result->signatories;
        $data['proponents']         =  $result->rfq->proponents;
        $data['min_bid']            =  $min;
        $data['today']              =  Carbon::now()->format('Y-m-d');

        $pdf = PDF::loadView('forms.canvass', ['data' => $data])
        ->setOption('margin-bottom', 30)
        ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('canvass.pdf');
    }

    /**
     * [viewCOP description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewCOP($id, CanvassingRepository $model)
    {
        $result     =   $model->with(['rfq', 'upr', 'signatories'])->findById($id);
        $min = min(array_column($result->rfq->proponents->toArray(), 'bid_amount'));

        $data['date']               =  $result->canvass_date." ". $result->canvass_time;

        $data['rfq_number']         =  $result->rfq->rfq_number;
        $data['total_amount']       =  $result->upr->total_amount;
        $data['header']             =  $result->upr->centers;
        $data['unit']               =  $result->upr->unit->short_code;
        $data['center']             =  $result->upr->centers->name;
        $data['venue']              =  $result->rfq->invitations->ispq->venue;
        $data['signatories']        =  $result->signatories;
        $data['proponents']         =  $result->rfq->proponents;
        $data['min_bid']            =  $min;

        $data['items']              =  $result->rfq->upr->items;
        $data['ref_number']         =  $result->rfq->upr->ref_number;

        $pdf = PDF::loadView('forms.cop', ['data' => $data])
        ->setOption('margin-bottom', 30)
        ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('cop.pdf');
    }

    /**
     * [viewROP description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewROP($id, CanvassingRepository $model)
    {
        $result     =   $model->with(['rfq', 'upr', 'signatories'])->findById($id);
        $min = min(array_column($result->rfq->proponents->toArray(), 'bid_amount'));

        $data['date']               =  $result->canvass_date." ". $result->canvass_time;

        $data['rfq_number']         =  $result->rfq->rfq_number;
        $data['total_amount']       =  $result->upr->total_amount;
        $data['header']             =  $result->upr->centers;
        $data['unit']               =  $result->upr->unit->short_code;
        $data['center']             =  $result->upr->centers->name;
        $data['venue']              =  $result->rfq->invitations->ispq->venue;
        $data['signatories']        =  $result->signatories;
        $data['proponents']         =  $result->rfq->proponents;
        $data['min_bid']            =  $min;

        $data['items']              =  $result->rfq->upr->items;
        $data['ref_number']         =  $result->rfq->upr->ref_number;

        $pdf = PDF::loadView('forms.rop', ['data' => $data])
            ->setOption('margin-bottom', 30)
            ->setOption('footer-html', route('pdf.footer'))
            ->setPaper('a4');

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('rop.pdf');
    }

    /**
     * [viewMOM description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewMOM($id, CanvassingRepository $model)
    {
        $result                 =   $model->with(['rfq', 'upr', 'signatories'])->findById($id);

        if($result->winners == null)
        {
            return redirect()->back()->with(['error' => 'No winner']);
        }

        $data['other_attendees']=   $result->other_attendees;
        $data['date_opened']    =   $result->canvass_date;
        $data['time_opened']    =   $result->canvass_time;
        $data['header']         =   $result->upr->centers;
        $data['venue']          =   $result->upr->invitations->ispq->venue;
        $data['time_closed']    =   $result->adjourned_time;
        $data['members']        =   $result->signatories;
        $data['others']         =   $result->other_attendees;
        $data['canvass']        =   $result;
        $data['center']         =  $result->upr->centers->name;
        $data['officer']        =   $result->officer;
        $data['resolution']     =   $result->resolution;

        $pdf = PDF::loadView('forms.mom', ['data' => $data])
        ->setOption('margin-bottom', 30)
        ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('mom.pdf');
    }

    /**
     * [viewLogs description]
     *
     * @param  [type]             $id    [description]
     * @param  BlankRFQRepository $model [description]
     * @return [type]                    [description]
     */
    public function viewLogs($id, CanvassingRepository $model, AuditLogRepository $logs)
    {

        $modelType  =   'Revlv\Procurements\Canvassing\CanvassingEloquent';
        $result     =   $logs->findByModelAndId($modelType, $id);
        $data_model =   $model->findById($id);

        return $this->view('modules.procurements.canvassing.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'model'         =>  $data_model,
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('Canvassing', 'procurements.canvassing.show', $data_model->id),
                new Breadcrumb('Logs'),
            ]
        ]);
    }
}
