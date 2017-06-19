<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

use \Revlv\Procurements\DeliveryOrder\DeliveryOrderRepository;
use \Revlv\Procurements\DeliveryOrder\DeliveryOrderRequest;
use \Revlv\Procurements\DeliveryOrder\Items\ItemRepository;
use \Revlv\Procurements\PurchaseOrder\PORepository;

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
    protected $items;

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        PORepository $po)
    {
        $this->validate($request, [
            'expected_date' =>  'required'
        ]);

        $po_model   =   $po->with(['items'])->findById($id);

        $inputs     =   [
            'expected_date' =>  $request->expected_date,
            'po_id'         =>  $id,
            'rfq_id'        =>  $po_model->rfq_id,
            'upr_id'        =>  $po_model->upr_id,
            'rfq_number'    =>  $po_model->rfq_number,
            'status'        =>  'ongoing',
            'upr_number'    =>  $po_model->upr_number,
            'created_by'    =>  \Sentinel::getUser()->id
        ];

        $result = $model->save($inputs);

        $items  =   [];

        foreach ($po_model->items as $item) {
            $items  =   [
                'order_id'      =>  $result->id,
                'description'   =>  $item->description,
                'quantity'      =>  $item->quantity,
                'unit'          =>  $item->unit,
                'price_unit'    =>  $item->price_unit,
                'total_amount'  =>  $item->total_amount,
            ];
        }

        DB::table('delivery_order_items')->insert($items);

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
    public function show($id, DeliveryOrderRepository $model)
    {
        $result     =   $model->with(['items'])->findById($id);

        return $this->view('modules.procurements.delivery.show',[
            'data'          =>  $result,
            'indexRoute'    =>  $this->baseUrl.'index',
            'completeRoute' =>  $this->baseUrl.'completed',
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update', $id],
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
        $result =   $model->findById($id);

        return $this->view('modules.procurements.delivery.edit',[
            'data'          =>  $result,
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

        $model->update($inputs, $id);

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
    public function completeOrder($id, DeliveryOrderRepository $model)
    {
        $date_completed     =   \Carbon\Carbon::now();

        $model->update(['date_completed' => $date_completed, 'status' => 'completed'], $id);

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
}
