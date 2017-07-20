<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use DB;
use Carbon\Carbon;

use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Procurements\InvitationToSubmitQuotation\ISPQRepository;
use \Revlv\Procurements\InvitationToSubmitQuotation\ISPQRequest;
use \Revlv\Procurements\InvitationToSubmitQuotation\UpdateRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\InvitationToSubmitQuotation\Quotations\QuotationRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use Validator;
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
            'createRoute'   =>  $this->baseUrl."create"
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
        BlankRFQRepository $rfq,
        HolidayRepository $holidays
        )
    {
        $this->validate($request, [
            'venue'                     =>  'required',
            'signatory_id'              =>  'required',
            'canvassing_date'           =>  'required',
            'canvassing_time'           =>  'required',
            'ispq_transaction_dates'    =>  'required',
        ]);

        $rfq_model              =   $rfq->findById($id);
        $upr_model              =   $rfq_model->upr;
        $transaction_date       =   Carbon::createFromFormat('Y-m-d', $request->get('ispq_transaction_dates') );

        $holiday_lists          =   $holidays->lists('id','holiday_date');

        $day_delayed            =   $rfq_model->completed_at->diffInDaysFiltered(function(Carbon $date)use ($holiday_lists) {
            return $date->isWeekday() && !in_array($date->format('Y-m-d'), $holiday_lists);
        }, $transaction_date);
        $day_delayed            =   $day_delayed - 1;

        // Validate Remarks when  delay
        $validator = Validator::make($request->all(),[
            'canvassing_date'  =>  'required',
            'action'            => 'required_with:remarks'
        ]);

        $validator->after(function ($validator)use($day_delayed, $request) {
            if ( $request->get('remarks') == null && $day_delayed >= 1) {
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
        $result =   $model->save([
            'prepared_by'       =>  \Sentinel::getUser()->id,
            'canvassing_date'   =>  $request->get('canvassing_date'),
            'canvassing_time'   =>  $request->get('canvassing_time'),
            'venue'             =>  $request->get('venue'),
            'signatory_id'      =>  $request->get('signatory_id'),
            'transaction_date'  =>  $request->get('ispq_transaction_dates'),
        ]);

        $data           =   [
            'ispq_id'           =>  $result->id,
            'rfq_id'            =>  $rfq_model->id,
            'upr_id'            =>  $rfq_model->upr_id,
            'description'       =>  $upr_model->project_name,
            'total_amount'      =>  $rfq_model->upr->total_amount,
            'upr_number'        =>  $rfq_model->upr_number,
            'rfq_number'        =>  $rfq_model->rfq_number,
            'delay_count'       =>  $day_delayed,
            'canvassing_date'   =>  $request->get('canvassing_date'),
            'canvassing_time'   =>  $request->get('canvassing_time'),
            'remarks'           =>  $request->get('remarks'),
            'action'            =>  $request->get('action'),
        ];

        $upr->update(['status' => 'Invitation Created',
            'delay_count'   => $day_delayed,
            'calendar_days' => $day_delayed + $upr_model->calendar_days,
            'action'        => $request->action,
            'remarks'       => $request->remarks
            ], $upr_model->id);

        $quotations->save($data);

        return redirect()->route('procurements.blank-rfq.show', $rfq_model->id)->with([
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
    public function viewPrint($id, ISPQRepository $model)
    {
        $result     =   $model->with(['quotations'])->findById($id);

        $data['transaction_date']   =  $result->transaction_date;
        $data['venue']              =  $result->venue;
        $data['signatories']        =  $result->signatories;
        $data['quotations']         =  $result->quotations;
        $pdf = PDF::loadView('forms.ispq', ['data' => $result])
            ->setOption('margin-bottom', 30)
            ->setOption('footer-html', route('pdf.footer'))
            ->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('ispq.pdf');
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
    public function update(UpdateRequest $request, $id, ISPQRepository $model)
    {
        $model->update($request->getData(), $id);

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
            'model'         =>  $data_model
        ]);
    }
}
