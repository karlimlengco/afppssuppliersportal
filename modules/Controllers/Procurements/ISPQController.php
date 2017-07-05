<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use DB;

use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Procurements\InvitationToSubmitQuotation\ISPQRepository;
use \Revlv\Procurements\InvitationToSubmitQuotation\ISPQRequest;
use \Revlv\Procurements\InvitationToSubmitQuotation\UpdateRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\InvitationToSubmitQuotation\Quotations\QuotationRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;

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
        BlankRFQRepository $rfq
        )
    {

        $this->validate($request, [
            'venue'                     =>  'required',
            'signatory_id'              =>  'required',
            'canvassing_date'           =>  'required',
            'canvassing_time'           =>  'required',
            'ispq_transaction_dates'    =>  'required',
        ]);

        $result =   $model->save([
            'prepared_by'       =>  \Sentinel::getUser()->id,
            'canvassing_date'   =>  $request->get('canvassing_date'),
            'canvassing_time'   =>  $request->get('canvassing_time'),
            'venue'             =>  $request->get('venue'),
            'signatory_id'      =>  $request->get('signatory_id'),
            'transaction_date'  =>  $request->get('ispq_transaction_dates'),
        ]);

        $rfq_model      =   $rfq->findById($id);
        $upr_model      =   $upr->findById($rfq_model->upr_id);
        $data           =   [
            'ispq_id'           =>  $result->id,
            'rfq_id'            =>  $rfq_model->id,
            'upr_id'            =>  $rfq_model->upr_id,
            'description'       =>  $upr_model->project_name,
            'total_amount'      =>  $rfq_model->upr->total_amount,
            'upr_number'        =>  $rfq_model->upr_number,
            'rfq_number'        =>  $rfq_model->rfq_number,
            'canvassing_date'   =>  $request->get('canvassing_date'),
            'canvassing_time'   =>  $request->get('canvassing_time'),
        ];

        $upr->update(['status' => 'Invitation Created'], $upr_model->id);

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
                'description'       =>  $request->get('description')[$key],
                'total_amount'      =>  $rfq_model->upr->total_amount,
                'upr_number'        =>  $rfq_model->upr_number,
                'rfq_number'        =>  $rfq_model->rfq_number,
                'canvassing_date'   =>  $rfq_model->deadline,
                'canvassing_time'   =>  $rfq_model->opening_time,
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
        $pdf = PDF::loadView('forms.ispq', ['data' => $result])->setOption('margin-bottom', 10)->setPaper('a4');

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
