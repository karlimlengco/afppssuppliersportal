<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;

use \Revlv\Procurements\NoticeOfAward\NOARepository;
use \Revlv\Procurements\Canvassing\CanvassingRepository;
use \Revlv\Procurements\Canvassing\CanvassingRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\RFQProponents\RFQProponentRepository;
use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;

class NoticeOfAwardController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "procurements.noa.";

    /**
     * [$blank description]
     *
     * @var [type]
     */
    protected $blank;
    protected $upr;
    protected $rfq;
    protected $noa;
    protected $signatories;
    protected $proponents;
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
     * [awardToProponent description]
     *
     * @return [type] [description]
     */
    public function awardToProponent(
        Request $request,
        CanvassingRepository $model,
        RFQProponentRepository $proponents,
        BlankRFQRepository $blank,
        UnitPurchaseRequestRepository $upr,
        NOARepository $noa,
        $canvasId,
        $proponentId
        )
    {
        $canvasModel        =   $model->findById($canvasId);
        $proponent_model    =   $proponents->with('supplier')->findById($proponentId);
        $supplier_name      =   $proponent_model->supplier->name;

        $this->validate($request,[
            'awarded_date'  =>   'required'
        ]);

        $data   =   [
            'canvass_id'    =>  $canvasId,
            'upr_id'        =>  $canvasModel->upr_id,
            'rfq_id'        =>  $canvasModel->rfq_id,
            'rfq_number'    =>  $canvasModel->rfq_number,
            'upr_number'    =>  $canvasModel->upr_number,
            'proponent_id'  =>  $proponentId,
            'awarded_by'    =>  \Sentinel::getUser()->id,
            'awarded_date'  =>  $request->awarded_date,
        ];

        $noa->save($data);

        // // Update canvass adjuourned time
        $model->update(['adjourned_time' => \Carbon\Carbon::now()], $canvasId);
        // // update upr
        $upr->update(['status' => "Awarded To $supplier_name"],  $canvasModel->upr_id);

        return redirect()->route('procurements.canvassing.show', $canvasId)->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     * [getDatatable description]
     *
     * @return [type]            [description]
     */
    public function getDatatable(NOARepository $model)
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
        return $this->view('modules.procurements.noa.index');
    }

    /**
     * [acceptNOA description]
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function acceptNOA(Request $request, NOARepository $model)
    {
        $id         =   $request->id;

        $validator = $this->validate($request->all(), [
            'file'          => 'required',
            'accepted_date' => 'required',
        ]);


        $data       =   [
            'accepted_date' =>  $request->accepted_date,
            'status'        =>  'approved',
            'file'          =>  ''
        ];

        if($request->file)
        {
            $file       = md5_file($request->file);
            $file       = $file.".".$request->file->getClientOriginalExtension();
            $data['file']   =   $file;
        }


        $result =   $model->update($data, $id);

        if($result && $request->has('file'))
        {
            $path       = $request->file->storeAs('noa-attachments', $file);
        }

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "NOA has been successfully accepted."
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(BlankRFQRepository $rfq)
    {
        $rfq_list   =   $rfq->lists('id', 'rfq_number');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(
        $id,
        NOARepository $model)
    {
        $result             =   $model->findById($id);

        return $this->view('modules.procurements.noa.edit',[
            'data'              =>  $result,
            'indexRoute'        =>  $this->baseUrl.'show',
            'modelConfig'       =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update-dates', $id],
                    'method'    =>  'PUT'
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
    public function store(CanvassingRequest $request, CanvassingRepository $model, BlankRFQRepository $rfq)
    {
        $rfq_model              =   $rfq->findById($request->rfq_id);
        $inputs                 =   $request->getData();
        $inputs['rfq_number']   =   $rfq_model->rfq_number;
        $inputs['upr_number']   =   $rfq_model->upr_number;
        $canvass_date           =   $inputs['canvass_date'];

        $rfq->update(['status' => "Canvasing ($canvass_date)"], $rfq_model->id);

        $result = $model->save($inputs);

        return redirect()->route($this->baseUrl.'edit', $result->id)->with([
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
        CanvassingRepository $model,
        NOARepository $noa,
        $id,
        RFQProponentRepository $proponents,
        SignatoryRepository $signatories,
        UnitPurchaseRequestRepository $upr)
    {
        $result             =   $noa->with(['winner', 'upr'])->findById($id);
        $canvass            =   $model->findById($result->canvass_id);
        $proponent_awardee  =   $result->winner->supplier;

        if(!$proponent_awardee)
        {
            return redirect()->route('procurements.blank-rfq.show', $id)->with([
                'success'    =>  'Awardee is not yet present. Go to canvassing and select proponent.'
            ]);
        }

        $upr_model          =   $upr->with(['centers','modes','unit','charges','accounts','terms','users'])->findByRFQId($proponent_awardee->rfq_id);

        $signatory_list     =   $signatories->lists('id','name');

        return $this->view('modules.procurements.noa.show',[
            'data'          =>  $result,
            'upr_model'     =>  $upr_model,
            'canvass'       =>  $canvass,
            'supplier'      =>  $proponent_awardee,
            'signatory_list'=>  $signatory_list,
            'printRoute'    =>  $this->baseUrl.'print',
            'indexRoute'    =>  $this->baseUrl.'index',
            'editRoute'     =>  $this->baseUrl.'edit',
            'modelConfig'   =>  [
                'receive_award' =>  [
                    'route'     =>  [$this->baseUrl.'update', $result->id]
                ],
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update-signatory', $id],
                    'method'    =>  'PUT'
                ],
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
    public function updateSignatory(Request $request, $id, NOARepository $model)
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateDates(
        Request $request,
        $id,
        RFQProponentRepository $rfq,
        BlankRFQRepository $blank,
        UnitPurchaseRequestRepository $upr,
        NOARepository $model
        )
    {
        $this->validate($request, [
            'awarded_date'              =>  'required',
            'award_accepted_date'       =>  'required',
            'accepted_date'             =>  'required',
        ]);

        $input  =   [
            'awarded_date'              =>  $request->awarded_date,
            'award_accepted_date'       =>  $request->award_accepted_date,
            'accepted_date'             =>  $request->accepted_date,
            'update_remarks'            =>  $request->update_remarks,
        ];

        $result             =   $model->findById($id);

        $model->update($input, $id);

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
        NOARepository $model
        )
    {
        $this->validate($request, [
            'received_by'           =>  'required',
            'award_accepted_date'   =>  'required',
        ]);

        $input  =   [
            'received_by'           =>  $request->received_by,
            'award_accepted_date'   =>  $request->award_accepted_date,
            'status'                =>  'accepted',
        ];

        $result             =   $model->findById($id);

        $model->update($input, $id);
        $upr->update(['status' => 'NOA Received'], $result->upr_id);

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
    public function viewPrint(
        $id,
        CanvassingRepository $model,
        BlankRFQRepository $blank,
        NOARepository $noa,
        UnitPurchaseRequestRepository $upr,
        RFQProponentRepository $rfq)
    {
        $noa_modal                  =   $noa->with(['winner','signatory'])->findById($id);

        if($noa_modal->signatory == null)
        {
            return redirect()->back()->with([
                'error'  => "Please select signatory first"
            ]);
        }

        $result                     =   $model->findById($noa_modal->canvass_id);
        $proponent_awardee          =   $noa_modal->winner->supplier;
        $rfq_model                  =   $blank->findById($result->rfq_id);
        $upr_model                  =   $upr->with(['unit'])->findById($rfq_model->upr_id);

        $data['transaction_date']   =   $noa_modal->awarded_date;
        $data['rfq_date']           =   $rfq_model->transaction_date;
        $data['supplier']           =   $proponent_awardee;
        $data['unit']               =   $upr_model->unit->description;
        $data['rfq_number']         =   $rfq_model->rfq_number;
        $data['total_amount']       =   $upr_model->total_amount;
        $data['bid_amount']         =   $noa_modal->winner->bid_amount;


        $data['canvass_date']       =   $result->canvass_date;
        $data['signatory']          =   $noa_modal->signatory;
        $data['project_name']       =   $upr_model->project_name;

        $pdf = PDF::loadView('forms.noa', ['data' => $data])->setOption('margin-left', 13)->setOption('margin-right', 13)->setOption('margin-bottom', 10)->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('noa.pdf');
    }

    /**
     * [downloadCopy description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function downloadCopy($id, NOARepository $model)
    {
        $result         = $model->findById($id);

        $directory      =   storage_path("app/noa-attachments/".$result->file);

        if(!file_exists($directory))
        {
            return 'Sorry. File does not exists.';
        }

        return response()->download($directory);
    }

    /**
     * [viewLogs description]
     *
     * @param  [type]             $id    [description]
     * @param  BlankRFQRepository $model [description]
     * @return [type]                    [description]
     */
    public function viewLogs($id, NOARepository $model, AuditLogRepository $logs)
    {

        $modelType  =   'Revlv\Procurements\NoticeOfAward\NOAEloquent';
        $result     =   $logs->findByModelAndId($modelType, $id);
        $data_model =   $model->findById($id);

        return $this->view('modules.procurements.noa.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'model'         =>  $data_model
        ]);
    }
}
