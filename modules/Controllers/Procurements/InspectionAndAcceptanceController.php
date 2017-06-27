<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use PDF;

use \Revlv\Procurements\NoticeOfAward\NOARepository;
use \Revlv\Settings\Signatories\SignatoryRepository;
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
    protected $noa;
    protected $signatories;
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
    public function acceptOrder($id, InspectionAndAcceptanceRepository $model, DeliveryOrderRepository $delivery)
    {
        $date_completed     =   \Carbon\Carbon::now();

        $result =   $model->update(['accepted_date' => $date_completed, 'status' => 'Accepted', 'accepted_by' => \Sentinel::getUser()->id], $id);

        $delivery->update(['status' => 'Accepted'], $result->dr_id);

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
     * [createFromDelivery description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function createFromDelivery($id)
    {

        $this->view('modules.procurements.inspection-acceptance.create-from-dr',[
            'indexRoute'    =>  'procurements.delivery-orders.show',
            'id'            =>  $id,
            'modelConfig'   =>  [
                'store' =>  [
                    'route'     =>  [$this->baseUrl.'create-from-delivery.store', $id]
                ]
            ]
        ]);
    }

    /**
     * [storeFromDelivery description]
     *
     * @param  [type]                            $id       [description]
     * @param  InspectionAndAcceptanceRequest    $request  [description]
     * @param  DeliveryOrderRepository           $delivery [description]
     * @param  InspectionAndAcceptanceRepository $model    [description]
     * @return [type]                                      [description]
     */
    public function storeFromDelivery(
        $id,
        Request $request,
        DeliveryOrderRepository $delivery,
        InspectionAndAcceptanceRepository $model)
    {
        $this->validate($request,[
            'inspection_date'       => 'required'
        ]);

        $dr_id                      =   $id;

        $dr_model                   =   $delivery->findById($dr_id);
        $items                      =   $request->only(['invoice_number', 'invoice_date']);

        $inputs                     =   [
            'inspection_date'   =>  $request->inspection_date,
            'nature_of_delivery'=>  $request->nature_of_delivery,
            'recommendation'    =>  $request->recommendation,
            'findings'          =>  $request->findings,
        ];

        $inputs['dr_id']            =   $dr_model->id;
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
        InspectionAndAcceptanceRepository $model,
        NOARepository $noa,
        SignatoryRepository $signatories)
    {
        $result             =   $model->with('invoices')->findById($id);

        $supplier           =   $noa->with('winner')->findByRFQ($result->rfq_id)->winner->supplier;
        $signatory_list     =   $signatories->lists('id','name');

        return $this->view('modules.procurements.inspection-acceptance.show',[
            'data'          =>  $result,
            'signatory_list'=>  $signatory_list,
            'supplier'      =>  $supplier,
            'indexRoute'    =>  $this->baseUrl.'index',
            'printRoute'    =>  $this->baseUrl.'print',
            'acceptRoute'   =>  $this->baseUrl.'accepted',
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

    /**
     * [viewPrint description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrint(
        $id,
        InspectionAndAcceptanceRepository $model,
        DeliveryOrderRepository $delivery,
        NOARepository $noa
        )
    {
        $model                      =  $model->with(['acceptor', 'inspector'])->findById($id);

        if($model->acceptor ==null || $model->inspector == null)
        {
            return redirect()->back()->with(['error' => 'please add signatory']);
        }

        $result                     =  $delivery->with(['upr', 'po'])->findById($model->dr_id);

        $noa_model                  =   $noa->with('winner')->findByRFQ($result->rfq_id)->winner->supplier;
        $data['today']              =  \Carbon\Carbon::now()->format("d F Y");
        $data['po_number']          =  $result->po->po_number;
        $data['purchase_date']      =  $result->po->purchase_date;
        $data['bid_amount']         =  $result->po->bid_amount;
        $data['project_name']       =  $result->upr->project_name;
        $data['center']             =  $result->upr->centers->name;
        $data['signatory']          =  $result->signatory;
        $data['winner']             =  $noa_model->name;
        $data['expected_date']      =  $result->expected_date;
        $data['items']              =  $result->po->items;
        $data['accepted_date']      =  $model->accepted_date;
        $data['inspection_date']    =  $model->inspection_date;
        $data['acceptor']           =  $model->acceptor;
        $data['inspector']          =  $model->inspector;

        $pdf = PDF::loadView('forms.iar', ['data' => $data])->setOption('margin-bottom', 0)->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('iar.pdf');
    }


    /**
     * [updateSignatory description]
     *
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function updateSignatory(Request $request, $id, InspectionAndAcceptanceRepository $model)
    {
        $this->validate($request, [
            'acceptance_signatory'   =>  'required',
            'inspection_signatory'   =>  'required',
        ]);
        $model->update(['acceptance_signatory' =>$request->acceptance_signatory, 'inspection_signatory' =>$request->inspection_signatory], $id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }
}
