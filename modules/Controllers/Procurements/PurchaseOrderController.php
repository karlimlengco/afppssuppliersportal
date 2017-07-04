<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use PDF;

use \Revlv\Procurements\PurchaseOrder\PORepository;
use \Revlv\Procurements\NoticeOfAward\NOARepository;
use \Revlv\Procurements\PurchaseOrder\Items\ItemRepository;
use \Revlv\Procurements\PurchaseOrder\PORequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;
use \Revlv\Procurements\RFQProponents\RFQProponentRepository;
use \Revlv\Settings\PaymentTerms\PaymentTermRepository;
use \Revlv\Settings\Signatories\SignatoryRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;

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
    protected $noa;
    protected $rfq;
    protected $terms;
    protected $proponents;
    protected $signatories;
    protected $audits;


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
     * [mfoApproved description]
     *
     * @param  [type]       $id      [description]
     * @param  Request      $request [description]
     * @param  PORepository $model   [description]
     * @return [type]                [description]
     */
    public function mfoApproved($id, Request $request, PORepository $model)
    {
        $this->validate($request, [
            'mfo_released_date' =>  'required',
            'mfo_received_date' =>  'required',
        ]);

        $inputs =   [
            'mfo_released_date' => $request->mfo_released_date,
            'mfo_received_date' => $request->mfo_received_date,
            'mfo_remarks'       => $request->mfo_remarks,
        ];

        $result =   $model->update($inputs, $id);

        $model->update(['status' => 'MFO Approved'], $id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);

    }

    /**
     * [pccoApproved description]
     *
     * @param  [type]       $id      [description]
     * @param  Request      $request [description]
     * @param  PORepository $model   [description]
     * @return [type]                [description]
     */
    public function pccoApproved($id, Request $request, PORepository $model)
    {
        $this->validate($request, [
            'funding_released_date' =>  'required',
            'funding_received_date' =>  'required',
        ]);

        $inputs =   [
            'funding_released_date' => $request->funding_released_date,
            'funding_received_date' => $request->funding_received_date,
            'funding_remarks'       => $request->funding_remarks,
        ];

        $result =   $model->update($inputs, $id);

        $model->update(['status' => 'Accounting Approved'], $id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Record has been successfully updated."
        ]);

    }

    public function createFromRfq(
        $id,
        BlankRFQRepository $rfq,
        PaymentTermRepository $terms,
        PORepository $model)
    {

        $term_lists =   $terms->lists('id','name');

        $this->view('modules.procurements.purchase-order.create-from-rfq',[
            'indexRoute'    =>  $this->baseUrl.'index',
            'term_lists'    =>  $term_lists,
            'rfq_id'        =>  $id,
            'modelConfig'   =>  [
                'store' =>  [
                    'route'     =>  [$this->baseUrl.'store-from-rfq',$id]
                ]
            ]
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
     * [storeFromRfq description]
     *
     * @param  [type]                        $id      [description]
     * @param  Request                       $request [description]
     * @param  PORepository                  $model   [description]
     * @param  ItemRepository                $items   [description]
     * @param  NOARepository                 $noa     [description]
     * @param  UnitPurchaseRequestRepository $upr     [description]
     * @param  BlankRFQRepository            $rfq     [description]
     * @return [type]                                 [description]
     */
    public function storeFromRfq(
        $id,
        Request $request,
        PORepository $model,
        ItemRepository $items,
        NOARepository $noa,
        UnitPurchaseRequestRepository $upr,
        BlankRFQRepository $rfq)
    {

        $this->validate($request,[
            'purchase_date'     => 'required',
            'payment_term'      => 'required',
        ]);

        $inputs                 =   [
            'purchase_date' =>  $request->purchase_date,
            'payment_term'  =>  $request->payment_term,
        ];

        $items                  =   $request->only([
            'item_description', 'quantity', 'unit_measurement', 'unit_price', 'total_amount'
        ]);

        $noa_model              =   $noa->with('winner')->findByRFQ($id);

        $split_upr              =   explode('-', $noa_model->rfq_number);
        $inputs['po_number']    =  "PO-".$split_upr[1]."-".$split_upr[2]."-".$split_upr[3]."-".$split_upr[4] ;

        $rfq_model              =   $rfq->findById($noa_model->rfq_id);

        $inputs['prepared_by']  =   \Sentinel::getUser()->id;
        $inputs['rfq_id']       =   $id;
        $inputs['upr_id']       =   $rfq_model->upr_id;
        $inputs['upr_number']   =   $rfq_model->upr_number;
        $inputs['rfq_number']   =   $rfq_model->rfq_number;
        $inputs['bid_amount']   =   $noa_model->winner->bid_amount;
        $inputs['status']       =   "pending";

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

        $upr->update(['status' => "PO Created"], $noa_model->upr_id);

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
        PORequest $request,
        PORepository $model,
        ItemRepository $items,
        BlankRFQRepository $rfq)
    {
        $items  =   $request->only(['item_description', 'quantity', 'unit_measurement', 'unit_price', 'total_amount']);
        $rfq_model              =   $rfq->with('upr')->findAwardeeById($request->rfq_id);

        $inputs                 =   $request->getData();
        $inputs['prepared_by']  =   \Sentinel::getUser()->id;
        $inputs['upr_id']       =   $rfq_model->upr_id;
        $inputs['upr_number']   =   $rfq_model->upr_number;
        $inputs['rfq_number']   =   $rfq_model->rfq_number;
        $inputs['bid_amount']   =   $rfq_model->bid_amount;
        $inputs['status']       =   "pending";
        $result = $model->save($inputs);

        $po_name   =   $rfq_model->upr->unit->name ."-". $rfq_model->upr->centers->name."-". $result->id;
        $po_name   =   str_replace(" ", "-", $po_name);

        $model->update(['po_number' => $po_name], $result->id);

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
        PORepository $model,
        RFQProponentRepository $proponents,
        SignatoryRepository $signatories,
        NOARepository $noa,
        UnitPurchaseRequestRepository $upr)
    {
        $result             =   $model->findById($id);
        $noa_model          =   $noa->with('winner')->findByRFQ($result->rfq_id);
        $supplier           =   $noa_model->winner->supplier;

        $signatory_list     =   $signatories->lists('id','name');
        return $this->view('modules.procurements.purchase-order.show',[
            'data'          =>  $result,
            'signatory_list'=>  $signatory_list,
            'supplier'      =>  $supplier,
            'indexRoute'    =>  $this->baseUrl.'index',
            'editRoute'     =>  $this->baseUrl.'edit',
            'modelConfig'   =>  [
                'mfo_approval' =>  [
                    'route'     =>  [$this->baseUrl.'mfo-approved', $id],
                    'method'    =>  'POST'
                ],
                'pcco_approval' =>  [
                    'route'     =>  [$this->baseUrl.'pcco-approved', $id],
                    'method'    =>  'POST'
                ],
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
    public function edit(
        $id,
        PORepository $model)
    {
        $result     =   $model->findById($id);

        return $this->view('modules.procurements.purchase-order.edit',[
            'data'          =>  $result,
            'indexRoute'    =>  $this->baseUrl.'show',
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update-dates', $id],
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
        $input  =   [
            'requestor_id'  =>  $request->requestor_id,
            'accounting_id' =>  $request->accounting_id,
            'approver_id'   =>  $request->approver_id,
            'coa_signatory' =>  $request->coa_signatory,
        ];

        $model->update($input, $id);

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
    public function updateDates(
        Request $request,
        $id,
        RFQProponentRepository $rfq,
        BlankRFQRepository $blank,
        PORepository $model
        )
    {

        $this->validate($request, [
            'update_remarks',
            'purchase_date',
            'funding_released_date',
            'funding_received_date',
            'mfo_released_date',
            'mfo_received_date',
            'coa_approved_date',
        ]);

        $input  =   [
            'update_remarks'        =>  $request->update_remarks,
            'purchase_date'         =>  $request->purchase_date,
            'funding_released_date' =>  $request->funding_released_date,
            'funding_received_date' =>  $request->funding_received_date,
            'mfo_released_date'     =>  $request->mfo_released_date,
            'mfo_received_date'     =>  $request->mfo_received_date,
            'coa_approved_date'     =>  $request->coa_approved_date,
        ];

        $model->update($input, $id);

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

    /**
     * [viewPrint description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrintTerms($id, PORepository $model)
    {
        $result                     =  $model->with(['rfq'])->findById($id);
        $data['transaction_date']   =  $result->rfq->transaction_date;
        $data['rfq_number']         =  $result->rfq_number;

        $pdf = PDF::loadView('forms.po-terms', ['data' => $data])->setOption('margin-bottom', 0)->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('po-terms.pdf');
        return $pdf->download('po-terms.pdf');
    }


    /**
     * [viewPrint description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrint($id, PORepository $model, NOARepository $noa, UnitPurchaseRequestRepository $upr)
    {
        $result                     =  $model->with(['terms','delivery','rfq','items'])->findById($id);
        $upr_model                  =  $upr->findById($result->upr_id);
        $noa_model                  =  $noa->with('winner')->findByRFQ($result->rfq_id)->winner->supplier;

        if($result->coa_signatories == null || $result->requestor == null || $result->accounting == null || $result->approver == null)
        {
            return redirect()->back()->with(['error' => 'Please add signatories']);
        }

        $data['po_number']          =  $result->po_number;
        $data['purchase_date']      =  $result->purchase_date;
        $data['transaction_date']   =  $result->rfq->transaction_date;
        $data['winner']             =  $noa_model;
        $data['rfq_number']         =  $result->rfq_number;
        $data['mode']               =  $upr_model->modes->name;
        $data['term']               =  $result->terms->name;
        $data['accounts']           =  $upr_model->accounts->new_account_code;
        $data['centers']            =  $upr_model->centers->name;
        $data['delivery']           =  $result->delivery;
        $data['items']              =  $result->items;
        $data['bid_amount']         =  $result->bid_amount;
        $data['requestor']          =  $result->requestor;
        $data['accounting']         =  $result->accounting;
        $data['approver']           =  $result->approver;
        $data['coa_signatories']    =  $result->coa_signatories;
        $data['mfo_release_date']   =  $result->mfo_release_date;
        $data['coa_approved_date']  =  $result->coa_approved_date;
        $data['funding_release_date']  =  $result->funding_release_date;

        $pdf = PDF::loadView('forms.po', ['data' => $data])->setOption('margin-bottom', 0)->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('po.pdf');
    }

    /**
     * [viewPrint description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrintCOA($id, PORepository $model, NOARepository $noa)
    {
        $result                     =  $model->with(['coa_signatories','rfq','upr'])->findById($id);
        $noa_model                  =   $noa->with('winner')->findByRFQ($result->rfq_id)->winner->supplier;

        if($result->coa_signatories == null)
        {
            return redirect()->back()->with(['error' => 'Please add signatory for COA']);
        }

        $data['transaction_date']   =  $result->rfq->transaction_date;
        $data['today']              =  \Carbon\Carbon::now()->format("d F Y");
        $data['rfq_number']         =  $result->rfq_number;
        $data['bid_amount']         =  $result->bid_amount;
        $data['project_name']       =  $result->upr->project_name;
        $data['winner']             =  $noa_model->name;
        $data['coa_signatory']      =  $result->coa_signatories;

        $pdf = PDF::loadView('forms.po-coa', ['data' => $data])->setOption('margin-bottom', 0)->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('po-coa.pdf');
        return $pdf->download('po-coa.pdf');
    }

    /**
     * [coaApproved description]
     *
     * @param  [type]       $id      [description]
     * @param  Request      $request [description]
     * @param  PORepository $model   [description]
     * @return [type]                [description]
     */
    public function coaApproved(
        $id,
        Request $request,
        UnitPurchaseRequestRepository $upr,
        PORepository $model)
    {
        $validator = $this->validate($request, [
            'file'              => 'required',
            'coa_approved_date' => 'required',
        ]);

        $file       = md5_file($request->file);
        $file       = $file.".".$request->file->getClientOriginalExtension();

        $data       =   [
            'coa_file'          =>  $file,
            'coa_approved_date' =>  $request->coa_approved_date,
            'coa_approved'      =>  \Sentinel::getUser()->id,
            'status'            =>  'COA Approved'
        ];

        $result =   $model->update($data, $id);

        if($result)
        {
            $path       = $request->file->storeAs('coa-approved-attachments', $file);
        }

        $upr->update(['status' => 'PO Approved'], $result->upr_id);

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Purchase Order has been successfully approved."
        ]);
    }

    /**
     * [downloadCoa description]
     *
     * @param  [type]       $id    [description]
     * @param  PORepository $model [description]
     * @return [type]              [description]
     */
    public function downloadCoa($id, PORepository $model)
    {
        $result         = $model->findById($id);

        $directory      =   storage_path("app/coa-approved-attachments/".$result->coa_file);

        if(!file_exists($directory))
        {
            return 'Sorry. File does not exists.';
        }

        return response()->download($directory);
    }


    /**
     * [viewLogs description]
     *
     * @param  [type]             $id    [description]
     * @param  BlankRFQRepository $model [description]
     * @return [type]                    [description]
     */
    public function viewLogs($id, PORepository $model, AuditLogRepository $logs)
    {

        $modelType  =   'Revlv\Procurements\PurchaseOrder\POEloquent';
        $result     =   $logs->findByModelAndId($modelType, $id);
        $data_model =   $model->findById($id);

        return $this->view('modules.procurements.purchase-order.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'model'         =>  $data_model
        ]);
    }
}
