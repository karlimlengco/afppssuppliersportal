<?php

namespace Revlv\Settings\CateredUnits;

use Illuminate\Http\Request;
use DB;
use Datatables;
use Excel;
use PDF;

use \Revlv\Settings\CateredUnits\CateredUnitRepository;
use \Revlv\Settings\CateredUnits\Attachments\AttachmentRepository;

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

        $file       = md5_file($request->file);
        $file       = $file.".".$request->file->getClientOriginalExtension();

        $validator = \Validator::make($request->all(), [
            'file'          => 'required',
            'name'          => 'required',
            'validity_date' => 'required',
            'amount'        => 'required',
        ]);

        $result     = $attachments->save([
            'unit_id'       =>  $id,
            'name'          =>  $request->name,
            'amount'          =>  $request->amount,
            'validity_date'          =>  $request->validity_date,
            'file_name'     =>  $file,
            'user_id'       =>  \Sentinel::getUser()->id,
            'upload_date'   =>  \Carbon\Carbon::now()
        ]);

        if($result)
        {
            $path       = $request->file->storeAs('unit-attachments', $file);
        }

        return redirect()->back()->with([
            'success'  => "Attachment has been successfully added."
        ]);
    }

    /**
     * [uploadAttachment description]
     *
     * @param  Request $request [description]
     * @param  [type]  $id      [description]
     * @return [type]           [description]
     */
    public function removeAttachment(
        Request $request,
        $id,
        AttachmentRepository $attachments)
    {

        $attachments->delete($id);
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

        $directory      =   storage_path("app/unit-attachments/".$result->file_name);

        if(!file_exists($directory))
        {
            return 'Sorry. File does not exists.';
        }

        return response()->download($directory);
    }
}