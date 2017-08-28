<?php

namespace Revlv\Procurements\UnitPurchaseRequests\Traits;

use Illuminate\Http\Request;
use App\Events\Event;
use DB;
use Datatables;
use Excel;
use PDF;
use \App\Support\Breadcrumb;

use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;
use \Revlv\Settings\AuditLogs\AuditLogRepository;
use \Revlv\Procurements\UnitPurchaseRequests\Attachments\AttachmentRepository;

trait FileTrait
{
    /**
     * [$accounts description]
     *
     * @var [type]
     */
    protected $logs;
    protected $attachments;
    protected $models;

    /**
     * [logs description]
     *
     * @param  [type]                        $id    [description]
     * @param  UnitPurchaseRequestRepository $model [description]
     * @param  AuditLogRepository            $logs  [description]
     * @return [type]                               [description]
     */
    public function viewLogs($id, UnitPurchaseRequestRepository $model, AuditLogRepository $logs)
    {
        $modelType  =   'Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestEloquent';
        $result     =   $logs->findByModelAndId($modelType, $id);
        $upr_model  =   $model->findById($id);

        return $this->view('modules.procurements.upr.logs',[
            'indexRoute'    =>  $this->baseUrl."show",
            'data'          =>  $result,
            'upr'           =>  $upr_model,
            'breadcrumbs' => [
                new Breadcrumb('Alternative', 'procurements.unit-purchase-requests.index'),
                new Breadcrumb($upr_model->upr_number, 'procurements.unit-purchase-requests.show', $upr_model->id),
                new Breadcrumb('Logs'),
            ]
        ]);
    }

    /**
     * [viewPrint description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewPrint($id, UnitPurchaseRequestRepository $model)
    {
        $result     =   $model->findById($id);

        if($result->requestor == null ||  $result->funders == null || $result->approver == null)
        {
            return redirect()->back()->with(['error' => 'please add signatory']);
        }

        $data['upr_number']         =  $result->upr_number;
        $data['ref_number']         =  $result->ref_number;
        $data['date_prepared']      =  $result->date_prepared;
        $data['mode']               =  ($result->modes) ? $result->modes->name : "Public Bidding";
        $data['center']             =  $result->centers->name;
        $data['charge']             =  $result->charges->name;
        $data['codes']              =  $result->accounts->name;
        $data['fund_validity']      =  $result->fund_validity;
        $data['terms']              =  ($result->terms) ? $result->terms->name : "N/A";
        $data['other_infos']        =  $result->other_infos;
        $data['items']              =  $result->items;
        $data['purpose']            =  $result->purpose;
        $data['total_amount']       =  $result->total_amount;
        $data['header']             =  $result->centers;
        $data['requestor']          =  explode('/', $result->requestor_text);
        $data['funder']             =  explode('/', $result->fund_signatory_text);
        $data['approver']           =  explode('/', $result->requestor_text);
        $pdf = PDF::loadView('forms.upr', ['data' => $data])
        ->setOption('margin-bottom', 30)
        ->setOption('footer-html', route('pdf.footer'));

        return $pdf->setOption('page-width', '8.5in')->setOption('page-height', '14in')->inline('upr.pdf');
    }



    /**
     * [uploadAttachment description]
     *
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function uploadAttachment(
        Request $request,
        $id,
        AttachmentRepository $attachments)
    {

        $file       = md5_file($request->file);
        $file       = $file.".".$request->file->getClientOriginalExtension();

        $validator = \Validator::make($request->all(), [
            'file' => 'required',
        ]);

        $result     = $attachments->save([
            'upr_id'        =>  $id,
            'name'          =>  $request->file->getClientOriginalName(),
            'file_name'     =>  $file,
            'user_id'       =>  \Sentinel::getUser()->id,
            'upload_date'   =>  \Carbon\Carbon::now()
        ]);

        if($result)
        {
            $path       = $request->file->storeAs('upr-attachments', $file);
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
    public function downloadAttachment(
        Request $request,
        $id,
        AttachmentRepository $attachments)
    {
        $result     = $attachments->findById($id);

        $directory      =   storage_path("app/upr-attachments/".$result->file_name);

        if(!file_exists($directory))
        {
            return 'Sorry. File does not exists.';
        }

        return response()->download($directory);
    }
}