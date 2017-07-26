<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;
use Carbon\Carbon;
use \App\Support\Breadcrumb;

use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Procurements\BlankRequestForQuotation\UpdateRequest;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\Suppliers\SupplierRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Settings\Holidays\HolidayRepository;
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
    protected $suppliers;
    protected $signatories;
    protected $audits;
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
    public function getDatatable(BlankRFQRepository $model)
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
        return $this->view('modules.procurements.blank-rfq.index',[
            'createRoute'   =>  $this->baseUrl."create",
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
        $upr_model              =   $upr->findById($request->upr_id);
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->transaction_date);
        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $day_delayed            =   $upr_model->date_prepared->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);

        $day_delayed            =   $day_delayed - 1;

        // Validate Remarks when  delay
        $validator = Validator::make($request->all(),[
            'transaction_date'  =>  'required',
            'action'            =>  'required_with:remarks',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('remarks') == null && $day_delayed > 1) {
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
        // Validate Remarks when  delay

        $inputs                 =   $request->getData();
        $inputs['upr_number']   =   $upr_model->upr_number;
        $inputs['processed_by'] =   \Sentinel::getUser()->id;
        $split_upr              =   explode('-', $upr_model->ref_number);
        $inputs['rfq_number']   =  "RFQ-".$split_upr[1]."-".$split_upr[2]."-".$split_upr[3]."-".$split_upr[4] ;

        $inputs['days']         =   $day_delayed;

        $result = $model->save($inputs);

        $upr->update([
            'status'        => 'Processing RFQ',
            'state'         => 'On-Going',
            'date_processed'=> \Carbon\Carbon::now(),
            'processed_by'  => \Sentinel::getUser()->id,
            'delay_count'   => ($day_delayed > 1 )? $day_delayed - 1 : 0,
            'calendar_days' => $day_delayed,
            'last_action'   => $request->action,
            'last_remarks'  => $request->remarks
            ], $upr_model->id);

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
    public function edit($id, BlankRFQRepository $model, UnitPurchaseRequestRepository $upr)
    {
        $result     =   $model->findById($id);
        $upr_list   =   $upr->lists('id', 'upr_number');

        return $this->view('modules.procurements.blank-rfq.edit',[
            'data'          =>  $result,
            'upr_list'      =>  $upr_list,
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
    public function update(UpdateRequest $request, $id, BlankRFQRepository $model)
    {
        $model->update($request->getData(), $id);

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

        $completed_at       =   createCarbon('Y-m-d',$request->completed_at);

        $holiday_lists      =   $holidays->lists('id','holiday_date');

        $day_delayed        =   $rfq->transaction_date->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $completed_at);

        $day_delayed            =   $day_delayed - 1;

        // Validate Remarks when  delay
        $validator = Validator::make($request->all(),[
            'completed_at'      =>  'required',
            'close_action'      =>  'required_with:close_remarks',
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('close_remarks') == null && $day_delayed >= 1 ) {
                $validator->errors()->add('close_remarks', 'This field is required when your process is delay');
            }
        });

        if ($validator->fails()){
            return redirect()
                        ->back()
                        ->with(['error' => 'Your process is delay. Please add remarks to continue.'])
                        ->withErrors($validator)
                        ->withInput();
        }

        if(count($rfq->proponents) == 0)
        {
            return redirect()->route($this->baseUrl.'show', $request->rfq_id)->with([
                'error'  => "RFQ cannot be close without proponents"
            ]);
        }

        $upr->update(['delay_count' => $rfq->upr->delay_count + $day_delayed], $rfq->upr->id);

        $model->update([
            'status'        => 'closed',
            'completed_at'  => $request->completed_at,
            'close_remarks' => $request->close_remarks,
            'close_action'  => $request->close_action,
            'close_days'    => $day_delayed,
            ], $request->rfq_id);

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
    public function viewPrint($id, BlankRFQRepository $model)
    {
        $result     =   $model->with(['upr'])->findById($id);
        // dd($result);
        $data['total_amount']       =  $result->upr->total_amount;
        $data['transaction_date']   =  $result->transaction_date;
        $data['rfq_number']         =  $result->rfq_number;
        $data['deadline']           =  $result->deadline." ".$result->opening_time;
        $data['items']              =  $result->upr->items;
        $pdf = PDF::loadView('forms.rfq', ['data' => $data])
            ->setOption('margin-bottom', 30)
            ->setOption('footer-html', route('pdf.footer'))
            ->setPaper('a4');

        return $pdf
            ->setOption('page-width', '8.27in')
            ->setOption('page-height', '11.69in')
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
