<?php

namespace Revlv\Settings\Suppliers;

use Illuminate\Http\Request;
use DB;
use Datatables;
use Excel;
use PDF;

use \Revlv\Settings\Suppliers\SupplierRepository;
use \Revlv\Settings\Suppliers\Attachments\AttachmentRepository;

trait AttachmentTrait
{
    /**
     * [$accounts description]
     *
     * @var [type]
     */
    protected $attachments;
    protected $models;


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

        $validator = $this->validate($request, [
            'type'          => 'required',
            'file'          => 'required',
            'name'          => 'required',
            'issued_date'   => 'required',
            'validity_date' => 'required',
            'ref_number' => 'required',
            'place' => 'required',
        ]);

        $file       = md5_file($request->file);
        $file       = $file.".".$request->file->getClientOriginalExtension();


        $result     = $attachments->save([
            'supplier_id'       =>  $id,
            'name'          =>  $request->name,
            'place'         =>  $request->place,
            'issued_date'   =>  $request->issued_date,
            'ref_number'    =>  $request->ref_number,
            'type'          =>  $request->type,
            'file_name'     =>  $file,
            'user_id'       =>  \Sentinel::getUser()->id,
            'upload_date'   =>  \Carbon\Carbon::now(),
            'validity_date'   =>  $request->validity_date
        ]);

        if($result)
        {
            $path       = $request->file->storeAs('supplier-attachments', $file);
        }

        return redirect()->back()->with([
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

        $directory      =   storage_path("app/supplier-attachments/".$result->file_name);

        if(!file_exists($directory))
        {
            return 'Sorry. File does not exists.';
        }

        return response()->download($directory);
    }
}