<?php

namespace Revlv\Controllers\Procurements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\Support\Breadcrumb;
use Auth;
use PDF;
use App\Events\Event;

use \Revlv\Settings\Forms\Header\HeaderRepository;
use \Revlv\Settings\Forms\RFQ\RFQRepository;
use \Revlv\Settings\Suppliers\SupplierRepository;
use \Revlv\Procurements\RFQProponents\RFQProponentRepository;
use \Revlv\Procurements\RFQProponents\RFQProponentRequest;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Procurements\ProponentAttachments\ProponentAttachmentRepository;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;

class RFQProponentController extends Controller
{

    /**
     * [Base Route of Controller]
     *
     * @var string
     */
    protected $baseUrl  =   "procurements.rfq-proponents.";

    /**
     * [$upr description]
     *
     * @var [type]
     */
    protected $upr;

    /**
     * [$rfq description]
     *
     * @var [type]
     */
    protected $rfq;

    /**
     * [$model description]
     *
     * @var [type]
     */
    protected $model;

    /**
     * [$attachments description]
     *
     * @var [type]
     */
    protected $attachments;
    protected $suppliers;
    protected $rfqForms;
    protected $headers;

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
    public function getDatatable(RFQProponentRepository $model)
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
        return $this->view('modules.procurements.rfq-proponent.index',[
            'createRoute'   =>  $this->baseUrl."create"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(UnitPurchaseRequestRepository $upr, BlankRFQRepository $rfq)
    {
        $rfq_list   =   $rfq->lists('id', 'rfq_number');
        $this->view('modules.procurements.rfq-proponent.create',[
            'indexRoute'    =>  $this->baseUrl.'index',
            'rfq_list'      =>  $rfq_list,
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
    public function store(RFQProponentRequest $request, RFQProponentRepository $model, BlankRFQRepository $rfq)
    {
        $inputs                 =   $request->getData();
        $inputs['prepared_by']  =   \Sentinel::getUser()->id;
        $result = $model->save($inputs);

        event(new Event($result->rfq->upr, $result->rfq->upr->ref_number." Added Proponent"));

        return redirect()->back()->with([
            'success'  => "New record has been successfully added."
        ]);
    }

    /**
     * [uploadAttachment description]
     *
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function uploadAttachment(Request $request, $id, ProponentAttachmentRepository $attachments)
    {

        $file       = md5_file($request->file);
        $file       = $file.".".$request->file->getClientOriginalExtension();

        $validator = \Validator::make($request->all(), [
            'file' => 'required',
        ]);

        $result     = $attachments->save([
            'proponent_id'  =>  $id,
            'name'          =>  $request->file->getClientOriginalName(),
            'file_name'     =>  $file,
            'user_id'       =>  \Sentinel::getUser()->id,
            'upload_date'   =>  \Carbon\Carbon::now()
        ]);

        if($result)
        {
            $path       = $request->file->storeAs('attachments', $file);
        }

        return redirect()->route($this->baseUrl.'show', $id)->with([
            'success'  => "Attachment has been successfully added."
        ]);
    }

    /**
     * [downloadAttachment description]
     *
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function downloadAttachment(Request $request, $id, ProponentAttachmentRepository $attachments)
    {
        $result     = $attachments->findById($id);

        $directory      =   storage_path("app/attachments/".$result->file_name);

        if(!file_exists($directory))
        {
            return 'Sorry. File does not exists.';
        }

        return response()->download($directory);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, RFQProponentRepository $model, SupplierRepository $suppliers)
    {
        $result     =   $model->with(['attachments'])->findById($id);
        $supplier   =   $suppliers->findAndGetStatus($result->proponents);

        return $this->view('modules.procurements.rfq-proponent.show',[
            'data'          =>  $result,
            'status'        =>  $supplier,
            'indexRoute'    =>  'procurements.blank-rfq.show',
            'modelConfig'   =>  [
                'add_attachment' =>  [
                    'route'     =>  [$this->baseUrl.'attachments.store', $id],
                ],
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update', $id],
                    'method'    => 'PUT'
                ]
            ],
            'breadcrumbs' => [
                new Breadcrumb('Alternative'),
                new Breadcrumb('RFQ Proponent'),
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, RFQProponentRepository $model,BlankRFQRepository $rfq)
    {
        $result     =   $model->findById($id);
        $rfq_list   =   $rfq->lists('id', 'rfq_number');

        return $this->view('modules.procurements.rfq-proponent.edit',[
            'data'          =>  $result,
            'rfq_list'      =>  $rfq_list,
            'indexRoute'    =>  $this->baseUrl.'index',
            'modelConfig'   =>  [
                'update' =>  [
                    'route'     =>  [$this->baseUrl.'update', $id],
                    'method'    =>  'PUT',
                    'novalidate'=>  'novalidate'
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
    public function update(Request $request, $id, RFQProponentRepository $model)
    {
        if($result->rfq->canvassing == null)
        {
          return redirect()
                      ->back()
                      ->with(['error' => 'Please Add bid amount on canvassing only.'])
                      ->withErrors($validator)
                      ->withInput();
        }
        $this->validate($request, ['bid_amount' => 'required', 'status' => 'required', 'remarks' => 'required_if:status,failed']);

        $result =   $model->update(['remarks' => $request->remarks, 'bid_amount' => $request->bid_amount, 'status' => $request->status], $id);


        return redirect()->route('procurements.canvassing.show', $result->rfq->canvassing->id)->with([
            'success'  => "Record has been successfully updated."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, RFQProponentRepository $model)
    {
        $model->delete($id);

        return redirect()->route($this->baseUrl.'index')->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }

    /**
     * [delete description]
     *
     * @param  [type]                 $id    [description]
     * @param  RFQProponentRepository $model [description]
     * @return [type]                        [description]
     */
    public function delete($id, RFQProponentRepository $model)
    {
        $result     =   $model->delete($id);

        return redirect()->route('procurements.blank-rfq.show',$result->rfq_id)->with([
            'success'  => "Record has been successfully deleted."
        ]);
    }

    /**
     * [viewPrint description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrint($id, RFQProponentRepository $model, RFQRepository $rfqForms, HeaderRepository $headers)
    {
        $result     =   $model->findById($id);
        $supplier   =   $result->supplier;
        $rfq        =   $result->rfq;
        $header                     =  $headers->findByUnit($result->rfq->upr->units);
        $data['unitHeader']         =  ($header) ? $header->content : "" ;
        $rfqFormsContent            =   $rfqForms->findByPCCO($result->rfq->upr->centers->id);
        $data['content']            =  ($rfqFormsContent) ? $rfqFormsContent->content : "";
        $deadline = "";

        if($rfq->upr->philgeps) {
          $deadline = $rfq->upr->philgeps->deadline_rfq." ".$rfq->upr->philgeps->opening_time;
        }elseif($rfq->completed_at)
        {
          $deadline = $rfq->completed_at->format('Y-m-d H:i');
        }


        $data['total_amount']       =  $rfq->upr->total_amount;
        $data['header']             =  $rfq->upr->centers;
        $data['transaction_date']   =  $rfq->transaction_date;
        $data['rfq_number']         =  $rfq->rfq_number;
        $data['deadline']           =  $deadline;
        $data['items']              =  $rfq->upr->items;
        $data['supplier']           =  $supplier;
        $data['chief']              =  explode('/', $rfq->signatory_chief);;

        $pdf = PDF::loadView('forms.rfq-proponents', ['data' => $data])
            ->setOption('margin-bottom', 30)
            ->setOption('footer-html', route('pdf.footer'));

        return $pdf
            ->setOption('page-width', '8.5in')
            ->setOption('page-height', '14in')
            ->inline('rfq.pdf');
    }
}
