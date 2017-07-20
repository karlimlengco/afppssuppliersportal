<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use \Carbon\Carbon;
use Validator;
use PDF;

use \Revlv\Procurements\Canvassing\CanvassingRepository;
use \Revlv\Procurements\Canvassing\Signatories\SignatoryRepository as CSignatoryRepository;
use \Revlv\Procurements\Canvassing\CanvassingRequest;
use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Procurements\RFQProponents\RFQProponentRepository;
use \Revlv\Settings\Holidays\HolidayRepository;

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
        HolidayRepository $holidays)
    {
        $rfq_model              =   $rfq->with('invitations')->findById($id);

        if($rfq_model->invitations == null)
        {
            return redirect()->back()->with([
                'error'     =>  'Create Invitation First'
            ]);
        }

        $transaction_date       =   Carbon::createFromFormat('Y-m-d',$request->open_canvass_date);

        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $ispq_transaction_date  = Carbon::createFromFormat('Y-m-d', $rfq_model->invitations->ispq->transaction_date);

        $day_delayed            =   $ispq_transaction_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        if($day_delayed != 0)
        {
            $day_delayed            =   $day_delayed - 1;
        }

        // Validate Remarks when  delay
        $validator = Validator::make($request->all(),[
            'open_canvass_date'  =>  'required',
            'action'            =>  'required_with:remarks',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('remarks') == null && $day_delayed > 2) {
                $validator->errors()->add('remarks', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Your process is delay. Please add remarks to continue.'])
                        ->withErrors($validator)
                        ->withInput();
        }

        $inputs['rfq_number']   =   $rfq_model->rfq_number;
        $inputs['upr_number']   =   $rfq_model->upr_number;
        $inputs['upr_id']       =   $rfq_model->upr_id;
        $inputs['days']         =   $day_delayed;
        $inputs['rfq_id']       =   $id;
        $inputs['canvass_date'] =   $request->open_canvass_date;
        $inputs['remarks']      =   $request->remarks;
        $inputs['action']       =   $request->action;
        $inputs['canvass_time'] =   \Carbon\Carbon::now()->format('H:i:s');
        $inputs['open_by']      =   \Sentinel::getUser()->id;
        $canvass_date           =   $request->open_canvass_date;
        $result = $model->save($inputs);

        $upr->update([
            'status' => "Open Canvass ($canvass_date)",
            'delay_count'   => ($day_delayed > 1 )? $day_delayed - 1 : 0,
            'calendar_days' => $day_delayed + $rfq_model->upr->calendar_days,
            'action'        => $request->action,
            'remarks'       => $request->remarks
            ], $rfq_model->upr_id);

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

        $current_signs  =   array_intersect_key( $signatory_lists, $my_signtories->toArray()  );

        return $this->view('modules.procurements.canvassing.show',[
            'data'              =>  $result,
            'signatory_lists'   =>  $signatory_lists,
            'current_signs'     =>  $current_signs,
            'proponent_list'    =>  $proponent_list,
            'indexRoute'        =>  $this->baseUrl.'index',
            'editRoute'         =>  $this->baseUrl.'edit',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, CanvassingRepository $model, BlankRFQRepository $rfq)
    {
        $result     =   $model->findById($id);
        $rfq_list   =   $rfq->lists('id', 'rfq_number');

        return $this->view('modules.procurements.canvassing.edit',[
            'data'          =>  $result,
            'rfq_list'      =>  $rfq_list,
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
    public function update(CanvassingRequest $request, $id, CanvassingRepository $model)
    {
        $model->update($request->getData(), $id);

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

        $mysignatories->deleteAllByCanvass($id);

        for ($i=0; $i < count($request->signatory_id); $i++) {
            $mysignatories->save([
                'signatory_id'  =>  $request->signatory_id[$i],
                'canvass_id'    =>  $id
            ]);
        }

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
        $data['total_amount']       =  $result->upr->total_amount;
        $data['unit']               =  $result->upr->unit->short_code;
        $data['center']             =  $result->upr->centers->name;
        $data['venue']              =  $result->rfq->invitations->ispq->venue;
        $data['signatories']        =  $result->signatories;
        $data['proponents']         =  $result->rfq->proponents;
        $data['min_bid']            =  $min;

        $pdf = PDF::loadView('forms.canvass', ['data' => $data])->setOption('margin-bottom', 10)->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('canvass.pdf');
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

        return $this->view('modules.procurements.ispq.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'model'         =>  $data_model
        ]);
    }
}
