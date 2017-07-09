<?php

namespace Revlv\Procurements\UnitPurchaseRequests\Traits;

use Illuminate\Http\Request;
use DB;
use Datatables;
use Excel;
use PDF;

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
        $result     =   $model->with([
            'centers',
            'modes',
            'charges',
            'accounts',
            'terms',
            'items',
            'requestor',
            'funders',
            'approver',
            ])->findById($id);

        if($result->requestor == null ||  $result->funders == null || $result->approver == null)
        {
            return redirect()->back()->with(['error' => 'please add signatory']);
        }

        $data['upr_number']         =  $result->upr_number;
        $data['ref_number']         =  $result->ref_number;
        $data['date_prepared']      =  $result->date_prepared;
        $data['mode']               =  $result->modes->name;
        $data['center']             =  $result->centers->name;
        $data['charge']             =  $result->charges->name;
        $data['codes']              =  $result->accounts->name;
        $data['fund_validity']      =  $result->fund_validity;
        $data['terms']              =  $result->terms->name;
        $data['other_infos']        =  $result->other_infos;
        $data['items']              =  $result->items;
        $data['purpose']            =  $result->purpose;
        $data['total_amount']       =  $result->total_amount;
        $data['requestor']          =  $result->requestor;
        $data['funder']             =  $result->funders;
        $data['approver']           =  $result->approver;

        $pdf = PDF::loadView('forms.upr', ['data' => $data])->setOption('margin-bottom', 0)->setPaper('a4');

        return $pdf->setOption('page-width', '8.27in')->setOption('page-height', '11.69in')->inline('upr.pdf');
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