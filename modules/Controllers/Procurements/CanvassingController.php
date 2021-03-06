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
use \Revlv\Settings\Forms\Header\HeaderRepository;
use \Revlv\Settings\Forms\PCCOHeader\PCCOHeaderRepository;
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
    public function getDatatable(CanvassingRepository $model)
    {
        return $model->getDatatable();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, CanvassingRepository $model)
    {
        return $this->view('modules.procurements.canvassing.index',[
            'resources'     =>  $model->paginateByRequest(10, $request),
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
        CanvassingRequest $request,
        CanvassingRepository $model,
        BlankRFQRepository $rfq,
        UnitPurchaseRequestRepository $upr,
        SignatoryRepository $signatories,
        CSignatoryRepository $mysignatories,
        HolidayRepository $holidays)
    {
        $upr_model              =   $upr->findById($id);
        $rfq_model              =   $upr_model->rfq;


        $transaction_date       =   Carbon::createFromFormat('Y-m-d',$request->open_canvass_date);

        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $ispq_transaction_date  =   $rfq_model->completed_at;
        // dd($transaction_date);
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
        $inputs['mfo']      =   $request->mfo;
        $inputs['unit_head']      =   $request->unit_head;
        $inputs['legal']      =   $request->legal;
        $inputs['secretary']      =   $request->secretary;

        $presiding  =   $signatories->findById($request->presiding_officer);
        $inputs['presiding_signatory']   =   $presiding->name."/".$presiding->ranks."/".$presiding->branch."/".$presiding->designation;

        $chief_sign  =   $signatories->findById($request->chief);
        $inputs['chief_signatory']   =   $chief_sign->name."/".$chief_sign->ranks."/".$chief_sign->branch."/".$chief_sign->designation;

        $unit_head  =   $signatories->findById($request->unit_head);
        $inputs['unit_head_signatory']   =   $unit_head->name."/".$unit_head->ranks."/".$unit_head->branch."/".$unit_head->designation;

        $mfo  =   $signatories->findById($request->mfo);
        $inputs['mfo_signatory']   =   $mfo->name."/".$mfo->ranks."/".$mfo->branch."/".$mfo->designation;

        $legal  =   $signatories->findById($request->legal);
        $inputs['legal_signatory']   =   $legal->name."/".$legal->ranks."/".$legal->branch."/".$legal->designation;

        $secretary  =   $signatories->findById($request->secretary);
        $inputs['secretary_signatory']   =   $secretary->name."/".$secretary->ranks."/".$secretary->branch."/".$secretary->designation;

        $result = $model->save($inputs);

        // for ($i=0; $i < count($request->members); $i++) {
        //     $mysignatories->save([
        //         'signatory_id'  =>  $request->members[$i],
        //         'canvass_id'    =>  $result->id
        //     ]);
        // }

        $upr_result =   $upr->update([
            'next_allowable'=> 2,
            'next_step'     => 'Prepare NOA',
            'next_due'      => $ispq_transaction_date->addDays(2),
            'last_date'     => $transaction_date,
            'status'        => "Canvass",
            'delay_count'   => $wd,
            'calendar_days' => $cd + $rfq_model->upr->calendar_days,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $rfq_model->upr_id);

        event(new Event($upr_result, $upr_result->upr_number." Canvass"));

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

        event(new Event($upr_result, $upr_result->upr_number." Failed Bid"));

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

        // $my_signtories  =   $result->signatories->pluck('signatory_id', 'signatory_id');
        $signatory_info     =   $result->signatories;

        $my_signtories      =   [$result->chief => $result->chief, $result->unit_head => $result->unit_head, $result->mfo => $result->mfo, $result->legal => $result->legal, $result->secretary => $result->secretary, $result->presiding_officer => $result->presiding_officer];

        $current_signs  =   array_intersect_key( $signatory_lists, $my_signtories);

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
        SignatoryRepository $signatories,
        CanvassingRepository $model)
    {
        $this->validate($request, [
          'canvass_time' => 'required',
          'canvass_date' => 'required',
        ]);
        $canvass_model      =   $model->findById($id);

        $inputs                         =   $request->getData();
        $inputs['presiding_officer']    =   $request->presiding_officer;
        $inputs['chief']                =   $request->chief;
        $inputs['other_attendees']      =   $request->other_attendees;

        if($canvass_model->presiding_officer != $request->presiding_officer)
        {
            $presiding  =   $signatories->findById($request->presiding_officer);
            $inputs['presiding_signatory']   =   $presiding->name."/".$presiding->ranks."/".$presiding->branch."/".$presiding->designation;
        }

        if($canvass_model->chief != $request->chief)
        {
            $chief_sign  =   $signatories->findById($request->chief);
            $inputs['chief_signatory']   =   $chief_sign->name."/".$chief_sign->ranks."/".$chief_sign->branch."/".$chief_sign->designation;
        }

        if($canvass_model->unit_head != $request->unit_head)
        {
            $unit_head  =   $signatories->findById($request->unit_head);
            $inputs['unit_head_signatory']   =   $unit_head->name."/".$unit_head->ranks."/".$unit_head->branch."/".$unit_head->designation;
        }

        if($canvass_model->mfo != $request->mfo)
        {
            $mfo  =   $signatories->findById($request->mfo);
            $inputs['mfo_signatory']   =   $mfo->name."/".$mfo->ranks."/".$mfo->branch."/".$mfo->designation;
        }

        if($canvass_model->legal != $request->legal)
        {
            $legal  =   $signatories->findById($request->legal);
            $inputs['legal_signatory']   =   $legal->name."/".$legal->ranks."/".$legal->branch."/".$legal->designation;
        }

        if($canvass_model->secretary != $request->secretary)
        {
            $secretary  =   $signatories->findById($request->secretary);
            $inputs['secretary_signatory']   =   $secretary->name."/".$secretary->ranks."/".$secretary->branch."/".$secretary->designation;
        }


        $result                 =   $model->update($inputs, $id);

        $upr_model              =   $upr->findById($result->upr_id);
        // $rfq_model              =   $rfq->with('invitations')->findById($id);
        $rfq_model              =   $upr_model->rfq;

        $transaction_date       =   Carbon::createFromFormat('Y-m-d',$request->canvass_date);

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
        $canvass    =   $model->findById($id);
        $canvass    =   $model->update([
            'chief_attendance' => 0,
            'unit_head_attendance' => 0,
            'mfo_attendance' => 0,
            'legal_attendance' => 0,
            'secretary_attendance' => 0,
        ], $id);

        $inputs         =   [
            'cop' => $request->cop,
            'rop' => $request->rop
        ];
        if($request->attendance != null){

            // dd($request->attendance);
          foreach($request->attendance as $key => $attendance)
          {
              if($attendance == 1)
              {
                  $inputs['chief_attendance'] =   1;

              }
              elseif($attendance == 2)
              {
                  $inputs['unit_head_attendance'] =   1;
              }
              elseif($attendance == 3)
              {
                  $inputs['mfo_attendance'] =   1;
              }
              elseif($attendance == 4)
              {
                  $inputs['legal_attendance'] =   1;
              }
              elseif($attendance == 5)
              {
                  $inputs['secretary_attendance'] =   1;
              }
              else
              {

              }
          }
        }
        $canvass    =   $model->update($inputs, $id);

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
    public function viewPrintLandscape($id, CanvassingRepository $model, HeaderRepository $headers, PCCOHeaderRepository $pccoHeaders)
    {
        $result     =   $model->with(['rfq', 'upr', 'signatories'])->findById($id);
        $array      = $result->rfq->proponents->toArray();
        $min = min(array_column(array_filter($array,function($v) {
        return $v["status"] == 'passed'; }), 'bid_amount'));

        $minProp = null;

        foreach($result->rfq->proponents as $propo)
        {
            if($propo->status == 'passed')
            {
                if($min == $propo->bid_amount){
                    $minProp = $propo;
                }
            }
        }

        if($result->canvass_time != null)
        {
          $data['date']               =  $result->canvass_date." ". $result->canvass_time;
        } else{

          $data['date']               =  $result->canvass_date." 00:00:00 ";
        }

        $header                     =  $pccoHeaders->findByPCCO($result->upr->procurement_office);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;

        $data['chief_attendance']   =  $result->chief_attendance;
        $data['unit_head_attendance']   =  $result->unit_head_attendance;
        $data['mfo_attendance']     =  $result->mfo_attendance;
        $data['legal_attendance']   =  $result->legal_attendance;
        $data['secretary_attendance']   =  $result->secretary_attendance;

        $data['rfq_number']         =  $result->rfq->rfq_number;
        $data['header']             =  $result->upr->centers;
        $data['place_of_delivery']  =  $result->upr->place_of_delivery;
        $data['total_amount']       =  $result->upr->total_amount;
        $data['unit']               =  $result->upr->unit->short_code;
        $data['center']             =  $result->upr->centers->name;
        $data['venue']              =  $result->upr->invitations->ispq->venue;
        $data['signatories']        =  $result->signatories;
        $data['proponents']         =  $result->rfq->proponents;
        $data['chief_signatory']    =  explode('/',$result->chief_signatory);
        $data['presiding']          =  explode('/', $result->presiding_signatory);
        $data['unit_head_signatory']=  explode('/', $result->unit_head_signatory);
        $data['mfo']                =  explode('/', $result->mfo_signatory);
        $data['legal']              =  explode('/', $result->legal_signatory);
        $data['sec']                =  explode('/', $result->secretary_signatory);
        $data['min_bid']            =  $min;
        $data['minProp']            =  $minProp;
        $data['today']              =  $result->canvass_date;
        $pdf = PDF::loadView('forms.landscape', ['data' => $data]);
        // ->setOption('margin-bottom', 30);
        // ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '11in')->setOption('page-height', '8.5in')->inline('canvass.pdf');
    }

    /**
     * [viewPrint description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrint($id, CanvassingRepository $model, HeaderRepository $headers, PCCOHeaderRepository $pccoHeaders)
    {
        $result     =   $model->with(['rfq', 'upr', 'signatories'])->findById($id);
        $array      = $result->rfq->proponents->toArray();
        $min = min(array_column(array_filter($array,function($v) {
        return $v["status"] == 'passed'; }), 'bid_amount'));

        $minProp = null;

        foreach($result->rfq->proponents as $propo)
        {
            if($propo->status == 'passed')
            {
                if($min == $propo->bid_amount){
                    $minProp = $propo;
                }
            }
        }

        if($result->canvass_time != null)
        {
          $data['date']               =  $result->canvass_date." ". $result->canvass_time;
        } else{

          $data['date']               =  $result->canvass_date." 00:00:00 ";
        }

        $header                     =  $pccoHeaders->findByPCCO($result->upr->procurement_office);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;

        $data['chief_attendance']   =  $result->chief_attendance;
        $data['unit_head_attendance']   =  $result->unit_head_attendance;
        $data['mfo_attendance']     =  $result->mfo_attendance;
        $data['legal_attendance']   =  $result->legal_attendance;
        $data['secretary_attendance']   =  $result->secretary_attendance;

        $data['rfq_number']         =  $result->rfq->rfq_number;
        $data['header']             =  $result->upr->centers;
        $data['place_of_delivery']  =  $result->upr->place_of_delivery;
        $data['total_amount']       =  $result->upr->total_amount;
        $data['unit']               =  $result->upr->unit->short_code;
        $data['center']             =  $result->upr->centers->name;
        $data['venue']              =  $result->upr->invitations->ispq->venue;
        $data['signatories']        =  $result->signatories;
        $data['proponents']         =  $result->rfq->proponents;
        $data['chief_signatory']    =  explode('/',$result->chief_signatory);
        $data['presiding']          =  explode('/', $result->presiding_signatory);
        $data['unit_head_signatory']=  explode('/', $result->unit_head_signatory);
        $data['mfo']                =  explode('/', $result->mfo_signatory);
        $data['legal']              =  explode('/', $result->legal_signatory);
        $data['sec']                =  explode('/', $result->secretary_signatory);
        $data['min_bid']            =  $min;
        $data['minProp']            =  $minProp;
        $data['today']              =  $result->canvass_date;
        $pdf = PDF::loadView('forms.canvass', ['data' => $data])
        ->setOption('margin-bottom', 30);
        // ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('canvass.pdf');
    }

    /**
     * [viewCOP description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewCOP($id, CanvassingRepository $model, HeaderRepository $headers, PCCOHeaderRepository $pccoHeaders)
    {
        $result     =   $model->with(['rfq', 'upr', 'signatories'])->findById($id);
        $min = min(array_column($result->rfq->proponents->toArray(), 'bid_amount'));


        if($result->canvass_time != null)
        {
          $data['date_to']               =  $result->canvass_date." ". $result->canvass_time;
        } else{

          $data['date_to']               =  $result->canvass_date." 00:00:00 ";
        }

        // $data['date_to']               =  $result->canvass_date." ". $result->canvass_time;
        $data['date_from']              =  $result->upr->date_processed;
        $data['date']                   =  $result->canvass_date;

        $header                     =  $pccoHeaders->findByPCCO($result->upr->procurement_office);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;

        if($result->cop == 1)
        {
            $signatory      =   $result->presiding_signatory;
        }
        elseif($result->cop == 2)
        {
            $signatory      =   $result->unit_head_signatory;
        }
        elseif($result->cop == 3)
        {
            $signatory      =   $result->mfo_signatory;
        }
        elseif($result->cop == 4)
        {
            $signatory      =   $result->legal_signatory;
        }
        else
        {
            $signatory      =   $result->secretary_signatory;
        }


        $data['rfq_number']         =  $result->rfq->rfq_number;
        $data['total_amount']       =  $result->upr->total_amount;
        $data['header']             =  $result->upr->centers;
        $data['unit']               =  $result->upr->unit->short_code;
        $data['center']             =  $result->upr->centers->name;
        $data['venue']              =  $result->upr->invitations->ispq->venue;
        $data['proponents']         =  $result->rfq->proponents;
        $data['min_bid']            =  $min;
        $data['signatory']          =  explode('/', $signatory);

        $data['items']              =  $result->rfq->upr->items;
        $data['ref_number']         =  $result->rfq->upr->ref_number;

        $pdf = PDF::loadView('forms.cop', ['data' => $data])
        ->setOption('margin-bottom', 30);
        // ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('cop.pdf');
    }

    /**
     * [viewROP description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewROP($id, CanvassingRepository $model, HeaderRepository $headers, PCCOHeaderRepository $pccoHeaders)
    {
        $result     =   $model->with(['rfq', 'upr', 'signatories'])->findById($id);
        $min = min(array_column($result->rfq->proponents->toArray(), 'bid_amount'));
        $data['date']               =  $result->canvass_date;

        $header                     =  $pccoHeaders->findByPCCO($result->upr->procurement_office);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;

        $data['rfq_number']         =  $result->rfq->rfq_number;
        $data['total_amount']       =  $result->upr->total_amount;
        $data['header']             =  $result->upr->centers;
        $data['unit']               =  $result->upr->unit->short_code;
        $data['center']             =  $result->upr->centers->name;
        $data['venue']              =  $result->upr->invitations->ispq->venue;
        $data['signatories']        =  $result->signatories;
        $data['proponents']         =  $result->rfq->proponents;
        $data['min_bid']            =  $min;

        $data['items']              =  $result->rfq->upr->items;
        $data['ref_number']         =  $result->rfq->upr->ref_number;
        if($result->rop == 1)
        {
            $signatory      =   $result->presiding_signatory;
        }
        elseif($result->rop == 2)
        {
            $signatory      =   $result->unit_head_signatory;
        }
        elseif($result->rop == 3)
        {
            $signatory      =   $result->mfo_signatory;
        }
        elseif($result->rop == 4)
        {
            $signatory      =   $result->legal_signatory;
        }else{
            $signatory      =   $result->secretary_signatory;
        }

        $data['signatory']          =  explode('/', $signatory);

        $pdf = PDF::loadView('forms.rop', ['data' => $data])
            ->setOption('margin-bottom', 30)
            // ->setOption('footer-html', route('pdf.footer'))
            ->setPaper('a4');

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('rop.pdf');
    }

    /**
     * [viewMOM description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewMOM($id, CanvassingRepository $model, HeaderRepository $headers, PCCOHeaderRepository $pccoHeaders)
    {
        $result                 =   $model->with(['rfq', 'upr', 'signatories'])->findById($id);

        if($result->winners == null)
        {
            return redirect()->back()->with(['error' => 'No winner']);
        }

        $header                     =  $pccoHeaders->findByPCCO($result->upr->procurement_office);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;

        $data['project_name']   =   $result->upr->project_name;
        $data['unit']           =   $result->upr->unit->short_code;
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
        $data['chief_attendance']   =  $result->chief_attendance;
        $data['unit_head_attendance']   =  $result->unit_head_attendance;
        $data['mfo_attendance']     =  $result->mfo_attendance;
        $data['legal_attendance']   =  $result->legal_attendance;
        $data['secretary_attendance']   =  $result->secretary_attendance;

        $data['chief_signatory']    =  explode('/',$result->chief_signatory);
        $data['presiding']          =  explode('/', $result->presiding_signatory);
        $data['unit_head_signatory']          =  explode('/', $result->unit_head_signatory);
        $data['mfo']                =  explode('/', $result->mfo_signatory);
        $data['legal']              =  explode('/', $result->legal_signatory);
        $data['sec']                =  explode('/', $result->secretary_signatory);
        $data['view_chief']         = false;

        if($result->chief_signatory != $result->presiding_signatory)
        {
          $data['view_chief'] = true;
        }

        $pdf = PDF::loadView('forms.mom', ['data' => $data])
        ->setOption('margin-bottom', 30);
        // ->setOption('footer-html', route('pdf.footer'));

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
