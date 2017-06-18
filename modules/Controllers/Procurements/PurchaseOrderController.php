<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

use \Revlv\Procurements\PurchaseOrder\PORepository;
use \Revlv\Procurements\PurchaseOrder\Items\ItemRepository;
use \Revlv\Procurements\PurchaseOrder\PORequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\RFQProponents\RFQProponentRepository;
use \Revlv\Settings\PaymentTerms\PaymentTermRepository;

class PurchaseOrderController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "procurements.purchase-orders.";

    /**
     * [$blank description]
     *
     * @var [type]
     */
    protected $blank;
    protected $items;
    protected $upr;
    protected $rfq;
    protected $terms;
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
        return $model->getDatatable();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.procurements.purchase-order.index',[
            'createRoute'   =>  $this->baseUrl."create"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(BlankRFQRepository $rfq, PaymentTermRepository $terms)
    {
        $rfq_list   =   $rfq->listsAccepted('id', 'rfq_number');

        $term_lists =   $terms->lists('id','name');

        $this->view('modules.procurements.purchase-order.create',[
            'indexRoute'    =>  $this->baseUrl.'index',
            'rfq_list'      =>  $rfq_list,
            'term_lists'    =>  $term_lists,
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
        PORequest $request,
        PORepository $model,
        ItemRepository $items,
        BlankRFQRepository $rfq)
    {
        $items  =   $request->only(['item_description', 'quantity', 'unit_measurement', 'unit_price', 'total_amount']);
        $rfq_model              =   $rfq->findAwardeeById($request->rfq_id);
        $inputs                 =   $request->getData();
        $inputs['prepared_by']  =   \Sentinel::getUser()->id;
        $inputs['upr_id']       =   $rfq_model->upr_id;
        $inputs['upr_number']   =   $rfq_model->upr_number;
        $inputs['rfq_number']   =   $rfq_model->rfq_number;
        $inputs['bid_amount']   =   $rfq_model->bid_amount;

        $result = $model->save($inputs);

        if($result)
        {
            for ($i=0; $i < count($items['item_description']); $i++) {
                $item_datas[]  =   [
                    'description'           =>  $items['item_description'][$i],
                    'quantity'              =>  $items['quantity'][$i],
                    'unit'                  =>  $items['unit_measurement'][$i],
                    'price_unit'            =>  $items['unit_price'][$i],
                    'total_amount'          =>  $items['total_amount'][$i],
                    'order_id'              =>  $result->id,
                ];
            }

            DB::table('purchase_order_items')->insert($item_datas);
        }

        $rfq->update(['status' => "PO Created"], $rfq_model->id);

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
        $id,
        PORepository $model,
        RFQProponentRepository $proponents,
        UnitPurchaseRequestRepository $upr)
    {
        $result             =   $model->findById($id);
        $proponent_awardee  =   $proponents->with('supplier')->findAwardeeByRFQId($result->rfq_id);
        $supplier           =   $proponent_awardee->supplier;
        $upr_model          =   $upr->with(['centers','modes','unit','charges','accounts','terms','users'])->findByRFQId($proponent_awardee->rfq_id);

        return $this->view('modules.procurements.purchase-order.show',[
            'data'          =>  $result,
            'upr_model'     =>  $upr_model,
            'supplier'      =>  $supplier,
            'awardee'       =>  $proponent_awardee,
            'indexRoute'    =>  $this->baseUrl.'index',
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
        PORepository $model,
        PaymentTermRepository $terms,
        BlankRFQRepository $rfq)
    {
        $result     =   $model->findById($id);
        $rfq_list   =   $rfq->listsAccepted('id', 'rfq_number');

        $term_lists =   $terms->lists('id','name');


        return $this->view('modules.procurements.purchase-order.edit',[
            'data'          =>  $result,
            'rfq_list'      =>  $rfq_list,
            'term_lists'    =>  $term_lists,
            'indexRoute'    =>  $this->baseUrl.'index',
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
    public function update(
        Request $request,
        $id,
        RFQProponentRepository $rfq,
        BlankRFQRepository $blank,
        PORepository $model
        )
    {
        $this->validate($request, [
            'received_by'   =>  'required',
            'award_accepted_date'   =>  'required',
        ]);

        $input  =   [
            'received_by'           =>  $request->received_by,
            'award_accepted_date'   =>  $request->award_accepted_date,
        ];

        $result             =   $model->findById($id);
        $proponent_awardee  =   $rfq->with('supplier')->findAwardeeByRFQId($result->rfq_id);

        $proponent          =   $rfq->update($input, $proponent_awardee->id);

        $blank->update(['status' => 'NOA Accepted'], $proponent->rfq_id);

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
    public function destroy($id, PORepository $model)
    {
        $model->delete($id);

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }
}
