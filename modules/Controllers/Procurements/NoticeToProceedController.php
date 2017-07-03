<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;

use \Revlv\Procurements\NoticeOfAward\NOARepository;
use \Revlv\Procurements\NoticeToProceed\NTPRepository;
use \Revlv\Procurements\PurchaseOrder\PORepository;
use \Revlv\Procurements\Canvassing\CanvassingRepository;
use \Revlv\Procurements\Canvassing\CanvassingRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\RFQProponents\RFQProponentRepository;
use \Revlv\Settings\Signatories\SignatoryRepository;

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
        return $this->view('modules.procurements.ntp.index');
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
        $supplier           =   $result->winner->supplier;
        $upr_model          =   $upr->with(['centers','modes','unit','charges','accounts','terms','users'])->findById($result->rfq_id);

        $signatory_list     =   $signatories->lists('id','name');

        return $this->view('modules.procurements.ntp.show',[
            'data'          =>  $result,
            'upr_model'     =>  $upr_model,
            'supplier'      =>  $supplier,
            'po_model'      =>  $po_model,
            'signatory_list'=>  $signatory_list,
            'indexRoute'    =>  $this->baseUrl.'index',
            'printRoute'    =>  $this->baseUrl.'print',
            'modelConfig'   =>  [
                'receive_ntp' =>  [
                    'route'     =>  [$this->baseUrl.'update', $id],
                    'method'    =>  'PUT'
                ],
                'create_nod' =>  [
                    'route'     =>  ['procurements.delivery-orders.create-purchase', $id]
                ],
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update-signatory', $id],
                    'method'    =>  'PUT'
                ],
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
        PORepository $po)
    {
        $po_model           =   $po->findById($request->po_id);
        $noa_model          =   $noa->findByRFQ($po_model->rfq_id);

        $this->validate($request, [
            'preparared_date'   =>  'required'
        ]);

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
        ];

        $upr->update(['status' => "NTP Created"], $po_model->upr_id);

        $result = $model->save($inputs);

        return redirect()->route($this->baseUrl.'show', $result->id)->with([
            'success'  => "New record has been successfully added."
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
        NTPRepository $model
        )
    {
        $this->validate($request, [
            'received_by'           =>  'required',
            'award_accepted_date'   =>  'required',
        ]);

        $input  =   [
            'received_by'           =>  $request->received_by,
            'award_accepted_date'   =>  $request->award_accepted_date,
            'status'                =>  "Accepted",
        ];

        $result             =   $model->update($input, $id);
        $upr->update(['status' => 'NTP Accepted'], $result->upr_id);

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
        $data['po_transaction_date']=   $result->po->po_number;
        $data['po_number']          =   $result->po->created_at;
        $data['rfq_number']         =   $result->rfq_number;
        $data['rfq_date']           =   $blank_model->transaction_date;
        $data['total_amount']       =   $result->po->bid_amount;
        $data['signatory']          =   $result->signatory;
        $data['project_name']       =   $upr_model->project_name;
        $data['today']              =   \Carbon\Carbon::now()->format("d F Y");

        $pdf = PDF::loadView('forms.ntp', ['data' => $data])->setOption('margin-left', 13)->setOption('margin-right', 13)->setOption('margin-bottom', 10)->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('ntp.pdf');
    }
}
