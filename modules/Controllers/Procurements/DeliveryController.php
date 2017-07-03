<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use PDF;

use \Revlv\Procurements\DeliveryOrder\DeliveryOrderRepository;
use \Revlv\Procurements\NoticeOfAward\NOARepository;
use \Revlv\Procurements\DeliveryOrder\DeliveryOrderRequest;
use \Revlv\Procurements\DeliveryOrder\Items\ItemRepository;
use \Revlv\Procurements\PurchaseOrder\PORepository;
use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;

class DeliveryController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "procurements.delivery-orders.";

    /**
     * [$model description]
     *
     * @var [type]
     */
    protected $model;

    /**
     * [$po description]
     *
     * @var [type]
     */
    protected $po;
    protected $noa;
    protected $items;
    protected $upr;
    protected $signatories;

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
    public function getDatatable(DeliveryOrderRepository $model)
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
        return $this->view('modules.procurements.delivery.index',[
            'createRoute'   =>  $this->baseUrl."create"
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeliveryOrderRequest $request, DeliveryOrderRepository $model)
    {
        $result = $model->save($request->getData());

        return redirect()->route($this->baseUrl.'edit', $result->id)->with([
            'success'  => "New record has been successfully added."
        ]);        //
    }

    /**
     * [createFromPurchase description]
     *
     * @param  [type]  $id      [description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function createFromPurchase($id,
        Request $request,
        DeliveryOrderRepository $model,
        UnitPurchaseRequestRepository $upr,
        PORepository $po)
    {
        $this->validate($request, [
            'expected_date'     =>  'required',
            'transaction_date'  =>  'required',
        ]);

        $po_model   =   $po->with(['items'])->findById($id);

        $inputs     =   [
            'expected_date'     =>  $request->expected_date,
            'transaction_date'  =>  $request->transaction_date,
            'po_id'             =>  $id,
            'rfq_id'            =>  $po_model->rfq_id,
            'upr_id'            =>  $po_model->upr_id,
            'rfq_number'        =>  $po_model->rfq_number,
            'status'            =>  'ongoing',
            'upr_number'        =>  $po_model->upr_number,
            'created_by'        =>  \Sentinel::getUser()->id
        ];

        $result = $model->save($inputs);

        $items  =   [];

        foreach ($po_model->items as $item) {
            $items[]  =   [
                'order_id'      =>  $result->id,
                'description'   =>  $item->description,
                'quantity'      =>  $item->quantity,
                'unit'          =>  $item->unit,
                'price_unit'    =>  $item->price_unit,
                'total_amount'  =>  $item->total_amount,
            ];
        }

        DB::table('delivery_order_items')->insert($items);

        $rfq->update(['status' => 'NOD Created'], $result->upr_id);

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
    public function show($id,
        DeliveryOrderRepository $model,
        SignatoryRepository $signatories)
    {
        $result             =   $model->with(['items'])->findById($id);
        $signatory_list     =   $signatories->lists('id','name');

        return $this->view('modules.procurements.delivery.show',[
            'data'          =>  $result,
            'signatory_list'=>  $signatory_list,
            'indexRoute'    =>  $this->baseUrl.'index',
            'editRoute'     =>  $this->baseUrl.'edit',
            'completeRoute' =>  $this->baseUrl.'completed',
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update-signatory', $id],
                    'method'    =>  'PUT'
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
    public function edit($id, DeliveryOrderRepository $model)
    {
        $result =   $model->with(['items'])->findById($id);

        return $this->view('modules.procurements.delivery.edit',[
            'data'          =>  $result,
            'indexRoute'    =>  $this->baseUrl.'index',
            'showRoute'     =>  $this->baseUrl.'show',
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
        $id,
        ItemRepository $items,
        DeliveryOrderRequest $request,
        DeliveryOrderRepository $model)
    {
        $inputs     =   $request->getData();
        $item_input =   $request->only(['ids', 'received_quantity']);

        for ($i=0; $i < count($item_input['ids']) ; $i++) {
            $items->update([
                'received_quantity' => $item_input['received_quantity'][$i]
                ], $item_input['ids'][$i]);
        }
        $inputs['received_by']  =   \Sentinel::getUser()->id;
        $model->update($inputs, $id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }


    /**
     * [updateSignatory description]
     *
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function updateSignatory(Request $request, $id, DeliveryOrderRepository $model)
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
     * [completeOrder description]
     *
     * @param  [type]                  $id    [description]
     * @param  DeliveryOrderRepository $model [description]
     * @return [type]                         [description]
     */
    public function completeOrder(Request $request, $id, DeliveryOrderRepository $model, UnitPurchaseRequestRepository $upr)
    {
        $date_completed     =   \Carbon\Carbon::now();

        $this->validate($request, [
            'date_delivered_to_coa'   =>  'required',
        ]);

        $result =   $model->update(['date_delivered_to_coa' => $request->date_delivered_to_coa, 'status' => 'completed', 'delivered_to_coa_by' => \Sentinel::getUser()->id], $id);

        $upr->update(['status' => 'Complete COA Delivery'], $result->upr_id);

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
    public function destroy($id, DeliveryOrderRepository $model)
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
        DeliveryOrderRepository $model,
        NOARepository $noa
        )
    {
        $result                     =  $model->with(['signatory','upr', 'po'])->findById($id);


        if($result->signatory == null)
        {
            return redirect()->back()->with([
                'error'  => "Please select signatory first"
            ]);
        }

        $noa_model                  =   $noa->with('winner')->findByRFQ($result->rfq_id)->winner->supplier;
        $data['today']              =  \Carbon\Carbon::now()->format("d F Y");
        $data['po_number']          =  $result->po->po_number;
        $data['transaction_date']   =  $result->created_at;
        $data['bid_amount']         =  $result->po->bid_amount;
        $data['project_name']       =  $result->upr->project_name;
        $data['center']             =  $result->upr->centers->name;
        $data['signatory']          =  $result->signatory;
        $data['winner']             =  $noa_model->name;
        $data['expected_date']      =  $result->expected_date;

        $pdf = PDF::loadView('forms.nod', ['data' => $data])->setOption('margin-bottom', 0)->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('nod.pdf');
    }
}
