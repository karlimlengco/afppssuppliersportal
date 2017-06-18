<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\Suppliers\SupplierRepository;

class BlankRFQController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "procurements.blank-rfq.";

    /**
     * [$upr description]
     *
     * @var [type]
     */
    protected $upr;

    /**
     * [$model description]
     *
     * @var [type]
     */
    protected $model;

    /**
     * [$suppliers description]
     *
     * @var [type]
     */
    protected $suppliers;

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
    public function getDatatable(BlankRFQRepository $model)
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
        return $this->view('modules.procurements.blank-rfq.index',[
            'createRoute'   =>  $this->baseUrl."create"
        ]);
    }

    /**
     * [getInfo description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getInfo($id, BlankRFQRepository $rfq)
    {
        $result =   $rfq->getInfo($id);
        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(UnitPurchaseRequestRepository $upr)
    {
        $upr_list   =   $upr->listPending('id', 'upr_number');
        $this->view('modules.procurements.blank-rfq.create',[
            'indexRoute'    =>  $this->baseUrl.'index',
            'upr_list'      =>  $upr_list,
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
    public function store(BlankRFQRequest $request, BlankRFQRepository $model, UnitPurchaseRequestRepository $upr)
    {
        $upr_model              =   $upr->with(['unit', 'centers'])->findById($request->upr_id);
        $inputs                 =   $request->getData();
        $inputs['upr_number']   =   $upr_model->upr_number;
        $result = $model->save($inputs);

        if($upr_model->unit && $upr_model->centers)
        {
            $rfq_name   =   $upr_model->unit->name ."-". $upr_model->centers->name."-". $result->id;
            $rfq_name   =   str_replace(" ", "-", $rfq_name);
        }

        $upr->update(['status' => 'processing', 'date_processed' => \Carbon\Carbon::now()], $upr_model->id);
        $model->update(['rfq_number' => $rfq_name], $result->id);

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
    public function show($id, BlankRFQRepository $model, SupplierRepository $suppliers)
    {
        $supplier_lists =   $suppliers->lists('id', 'name');
        $result         =   $model->with(['proponents','upr'])->findById($id);

        $exist_supplier =   $result->proponents->pluck('proponents')->toArray();
        foreach($exist_supplier as $list)
        {
            unset($supplier_lists[$list]);
        }

        return $this->view('modules.procurements.blank-rfq.show',[
            'supplier_lists'=>  $supplier_lists,
            'data'          =>  $result,
            'indexRoute'    =>  $this->baseUrl.'index',
            'editRoute'     =>  $this->baseUrl.'edit',
            'modelConfig'   =>  [
                'add_proponents'   => [
                    'route' => 'procurements.rfq-proponents.store',
                    'method'=> 'DELETE'
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
    public function edit($id, BlankRFQRepository $model, UnitPurchaseRequestRepository $upr)
    {
        $result     =   $model->findById($id);
        $upr_list   =   $upr->lists('id', 'upr_number');

        return $this->view('modules.procurements.blank-rfq.edit',[
            'data'          =>  $result,
            'upr_list'      =>  $upr_list,
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
    public function update(BlankRFQRequest $request, $id, BlankRFQRepository $model)
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
    public function destroy($id, BlankRFQRepository $model)
    {
        $model->delete($id);

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }
}
