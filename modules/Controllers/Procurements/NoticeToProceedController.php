<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

use \Revlv\Procurements\PurchaseOrder\PORepository;
use \Revlv\Procurements\Canvassing\CanvassingRepository;
use \Revlv\Procurements\Canvassing\CanvassingRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\RFQProponents\RFQProponentRepository;

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

    /**
     * [$upr description]
     *
     * @var [type]
     */
    protected $upr;

    /**
     * [$upr description]
     *
     * @var [type]
     */
    protected $rfq;

    /**
     *
     *
     * @var [type]
     */
    protected $proponents;

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
        RFQProponentRepository $proponents,
        UnitPurchaseRequestRepository $upr)
    {
        $result             =   $model->with(['delivery'])->findById($id);
        $proponent_awardee  =   $proponents->with('supplier')->findAwardeeByRFQId($result->rfq_id);
        $supplier           =   $proponent_awardee->supplier;
        $upr_model          =   $upr->with(['centers','modes','unit','charges','accounts','terms','users'])->findByRFQId($proponent_awardee->rfq_id);

        return $this->view('modules.procurements.ntp.show',[
            'data'          =>  $result,
            'upr_model'     =>  $upr_model,
            'supplier'      =>  $supplier,
            'awardee'       =>  $proponent_awardee,
            'indexRoute'    =>  $this->baseUrl.'index',
            'modelConfig'   =>  [
                'receive_ntp' =>  [
                    'route'     =>  [$this->baseUrl.'update', $id],
                    'method'    =>  'PUT'
                ],
                'create_nod' =>  [
                    'route'     =>  ['procurements.delivery-orders.create-purchase', $id]
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
}
