<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

use \Revlv\Procurements\DeliveryOrder\DeliveryOrderRepository;
use \Revlv\Procurements\InspectionAndAcceptance\InspectionAndAcceptanceRepository;
use \Revlv\Procurements\InspectionAndAcceptance\InspectionAndAcceptanceRequest;
use \Revlv\Procurements\RFQProponents\RFQProponentRepository;

class InspectionAndAcceptanceController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "procurements.inspection-and-acceptance.";

    /**
     * [$model description]
     *
     * @var [type]
     */
    protected $model;

    /**
     * [$devivery description]
     *
     * @var [type]
     */
    protected $devivery;
    protected $rfq;

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
    public function getDatatable(InspectionAndAcceptanceRepository $model)
    {
        return $model->getDatatable();
    }

    /**
     * [acceptDelivery description]
     *
     * @return [type] [description]
     */
    public function acceptOrder($id, InspectionAndAcceptanceRepository $model)
    {
        $date_completed     =   \Carbon\Carbon::now();

        $model->update(['accepted_date' => $date_completed, 'status' => 'Accepted', 'accepted_by' => \Sentinel::getUser()->id], $id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->view('modules.procurements.inspection-acceptance.index',[
            'createRoute'   =>  $this->baseUrl."create"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(DeliveryOrderRepository $delivery)
    {
        $delivery_lists =   $delivery->listCompleted('id', 'delivery_number');
        $this->view('modules.procurements.inspection-acceptance.create',[
            'delivery_lists'=>  $delivery_lists,
            'indexRoute'    =>  $this->baseUrl.'index',
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
        InspectionAndAcceptanceRequest $request,
        DeliveryOrderRepository $delivery,
        InspectionAndAcceptanceRepository $model)
    {
        $dr_id                      =   $request->dr_id;
        $inputs                     =   $request->getData();
        $dr_model                   =   $delivery->update(['status' => 'ongoing inspection'], $dr_id);
        $items                      =   $request->only(['invoice_number', 'invoice_date']);

        $inputs['rfq_id']           =   $dr_model->rfq_id;
        $inputs['upr_id']           =   $dr_model->upr_id;
        $inputs['rfq_number']       =   $dr_model->rfq_number;
        $inputs['upr_number']       =   $dr_model->upr_number;
        $inputs['delivery_number']  =   $dr_model->delivery_number;
        $inputs['prepared_by']      =   \Sentinel::getUser()->id;
        $inputs['status']           =   'ongoing';

        $result = $model->save($inputs);
        if($result)
        {
            $invoices                   =   [];
            for ($i=0; $i < count($items['invoice_number']); $i++) {
                $invoices[]  =   [
                    'invoice_number'    =>  $items['invoice_number'][$i],
                    'invoice_date'      =>  $items['invoice_date'][$i],
                    'acceptance_id'     =>  $result->id,
                ];
            }

            DB::table('inspection_acceptance_invoices')->insert($invoices);
        }

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
        RFQProponentRepository $proponents,
        InspectionAndAcceptanceRepository $model)
    {
        $result             =   $model->with('invoices')->findById($id);

        $proponent_awardee  =   $proponents->with('supplier')->findAwardeeByRFQId($result->rfq_id);
        $supplier           =   $proponent_awardee->supplier;

        return $this->view('modules.procurements.inspection-acceptance.show',[
            'data'          =>  $result,
            'supplier'      =>  $supplier,
            'indexRoute'    =>  $this->baseUrl.'index',
            'acceptRoute'   =>  $this->baseUrl.'accepted',
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, InspectionAndAcceptanceRepository $model)
    {
        $result =   $model->findById($id);

        return $this->view('modules.procurements.inspection-acceptance.edit',[
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
    public function update(InspectionAndAcceptanceRequest $request, $id, InspectionAndAcceptanceRepository $model)
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
    public function destroy($id, InspectionAndAcceptanceRepository $model)
    {
        $model->delete($id);

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }
}
