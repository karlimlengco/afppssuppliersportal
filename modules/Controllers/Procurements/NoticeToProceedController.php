<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;

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
    public function getDatatable(PORepository $model)
    {
        return $model->getNTPDatatable();
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
        PORepository $model,
        SignatoryRepository $signatories,
        RFQProponentRepository $proponents,
        UnitPurchaseRequestRepository $upr)
    {
        $result             =   $model->with(['delivery'])->findById($id);
        $proponent_awardee  =   $proponents->with('supplier')->findAwardeeByRFQId($result->rfq_id);
        $supplier           =   $proponent_awardee->supplier;
        $upr_model          =   $upr->with(['centers','modes','unit','charges','accounts','terms','users'])->findByRFQId($proponent_awardee->rfq_id);

        $signatory_list     =   $signatories->lists('id','name');

        return $this->view('modules.procurements.ntp.show',[
            'data'          =>  $result,
            'upr_model'     =>  $upr_model,
            'supplier'      =>  $supplier,
            'signatory_list'=>  $signatory_list,
            'awardee'       =>  $proponent_awardee,
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
     * [updateSignatory description]
     *
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function updateSignatory(Request $request, $id, PORepository $model)
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
        PORepository $model
        )
    {
        $this->validate($request, [
            'received_by'           =>  'required',
            'award_accepted_date'   =>  'required',
        ]);

        $input  =   [
            'received_by'           =>  $request->received_by,
            'award_accepted_date'   =>  $request->award_accepted_date,
            'status'                =>  "NTP Accepted",
        ];

        $result             =   $model->update($input, $id);

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
        PORepository $model,
        UnitPurchaseRequestRepository $upr,
        BlankRFQRepository $blank,
        RFQProponentRepository $proponents)
    {
        $result                     =   $model->with('signatories')->findById($id);
        $proponent_awardee          =   $proponents->with('supplier')->findAwardeeByRFQId($result->rfq_id);
        $supplier                   =   $proponent_awardee->supplier;
        $blank_model                =   $blank->findById($result->rfq_id);
        $upr_model                  =   $upr->findById($result->upr_id);
        $data['transaction_date']   =   $result->award_accepted_date;
        $data['supplier']           =   $supplier;
        $data['po_number']          =   $result->po_number;
        $data['rfq_number']         =   $result->rfq_number;
        $data['rfq_date']           =   $blank_model->transaction_date;
        $data['total_amount']       =   $upr_model->total_amount;
        $data['signatory']          =   $result->signatories;
        // dd($result);
        // $data['venue']              =  $result->venue;
        // $data['quotations']         =  $result->quotations;
        $pdf = PDF::loadView('forms.ntp', ['data' => $data])->setOption('margin-left', 13)->setOption('margin-right', 13)->setOption('margin-bottom', 10)->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->download('ntp.pdf');
    }
}
