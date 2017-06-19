<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRequest;
use \Revlv\Procurements\Items\ItemRepository;
use \Revlv\Settings\AccountCodes\AccountCodeRepository;
use \Revlv\Settings\Chargeability\ChargeabilityRepository;
use \Revlv\Settings\ModeOfProcurements\ModeOfProcurementRepository;
use \Revlv\Settings\ProcurementCenters\ProcurementCenterRepository;
use \Revlv\Settings\PaymentTerms\PaymentTermRepository;
use \Revlv\Settings\Units\UnitRepository;

class UPRController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "procurements.unit-purchase-requests.";

    /**
     * [$accounts description]
     *
     * @var [type]
     */
    protected $accounts;
    protected $chargeability;
    protected $modes;
    protected $centers;
    protected $terms;
    protected $items;
    protected $units;

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
    public function getDatatable(UnitPurchaseRequestRepository $model)
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
        return $this->view('modules.procurements.upr.index',[
            'createRoute'   =>  $this->baseUrl."create"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(
        AccountCodeRepository $accounts,
        ChargeabilityRepository $chargeability,
        ModeOfProcurementRepository $modes,
        ProcurementCenterRepository $centers,
        UnitRepository $units,
        PaymentTermRepository $terms)
    {

        $account_codes      =    $accounts->lists('id', 'new_account_code');
        $charges            =    $chargeability->lists('id', 'name');
        $procurement_modes  =    $modes->lists('id', 'name');
        $procurement_center =    $centers->lists('id', 'name');
        $payment_terms      =    $terms->lists('id', 'name');
        $unit               =    $units->lists('id', 'name');
        // $this->permissions->lists('permission','description')
        $this->view('modules.procurements.upr.create',[
            'indexRoute'        =>  $this->baseUrl.'index',
            'account_codes'     =>  $account_codes,
            'payment_terms'     =>  $payment_terms,
            'unit'              =>  $unit,
            'charges'           =>  $charges,
            'procurement_modes' =>  $procurement_modes,
            'procurement_center'=>  $procurement_center,
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
    public function store(UnitPurchaseRequestRequest $request, UnitPurchaseRequestRepository $model)
    {
        $items  =   $request->only(['item_description', 'quantity', 'unit_measurement', 'unit_price', 'total_amount']);
        $procs  =   $request->getData();

        $total_amount   =   array_sum($items['total_amount']);
        $prepared_by    =   \Sentinel::getUser()->id;
        $item_datas     =   [];

        $procs['total_amount']  =   $total_amount;
        $procs['prepared_by']   =   $prepared_by;

        $result = $model->save($procs);

        if($result)
        {
            for ($i=0; $i < count($items['item_description']); $i++) {
                $item_datas[]  =   [
                    'item_description'      =>  $items['item_description'][$i],
                    'quantity'              =>  $items['quantity'][$i],
                    'unit_measurement'      =>  $items['unit_measurement'][$i],
                    'unit_price'            =>  $items['unit_price'][$i],
                    'total_amount'          =>  $items['total_amount'][$i],
                    'upr_number'            =>  $request->get('upr_number'),
                    'afpps_ref_number'      =>  $request->get('afpps_ref_number'),
                    'prepared_by'           =>  $prepared_by,
                    'date_prepared'         =>  $request->get('date_prepared'),
                    'upr_id'                =>  $result->id
                ];
            }

            DB::table('unit_purchase_request_items')->insert($item_datas);
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
    public function show($id, UnitPurchaseRequestRepository $model)
    {
        $result =   $model->with(['philgeps', 'rfq', 'canvassing', 'purchase_order', 'delivery_order'])->findById($id);

        return $this->view('modules.procurements.upr.show',[
            'data'          =>  $result,
            'indexRoute'    =>  $this->baseUrl.'index',
            'editRoute'     =>  $this->baseUrl.'edit',
            'modelConfig'   =>  [
                'request_quotation' =>  [
                    'route'     =>  'procurements.blank-rfq.store',
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
    public function edit($id,
        AccountCodeRepository $accounts,
        ChargeabilityRepository $chargeability,
        ModeOfProcurementRepository $modes,
        ProcurementCenterRepository $centers,
        UnitPurchaseRequestRepository $model,
        UnitRepository $units,
        PaymentTermRepository $terms)
    {
        $result =   $model->findById($id);

        $account_codes      =    $accounts->lists('id', 'new_account_code');
        $charges            =    $chargeability->lists('id', 'name');
        $procurement_modes  =    $modes->lists('id', 'name');
        $procurement_center =    $centers->lists('id', 'name');
        $payment_terms      =    $terms->lists('id', 'name');
        $unit               =    $units->lists('id', 'name');

        return $this->view('modules.procurements.upr.edit',[
            'data'              =>  $result,
            'indexRoute'        =>  $this->baseUrl.'show',
            'account_codes'     =>  $account_codes,
            'payment_terms'     =>  $payment_terms,
            'charges'           =>  $charges,
            'unit'              =>  $unit,
            'procurement_modes' =>  $procurement_modes,
            'procurement_center'=>  $procurement_center,
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
    public function update(UnitPurchaseRequestRequest $request, $id, UnitPurchaseRequestRepository $model)
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
    public function destroy($id)
    {
        //
    }
}
