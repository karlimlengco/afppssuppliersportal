<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use \Revlv\Settings\Suppliers\Attachments\AttachmentRepository;

class SupplierAttachmentsController extends ApiController
{
    protected $units;

    public function index(Request $request, AttachmentRepository $units)
    {
        $unitList = $units->all();

        return response()->json([
            'data' => $unitList,
        ]);
    }

    /**
     * [listAll description]
     *
     * @param  Request        $request [description]
     * @param  AttachmentRepository $units    [description]
     * @return [type]                  [description]
     */
    public function listAll(Request $request, AttachmentRepository $units)
    {
        $units = $units->getAllAdmins();
        return response()->json([
            'data' => $units,
        ]);
    }

    /**
     * [store description]
     *
     * @param  Request        $request [description]
     * @param  AttachmentRepository $units    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, AttachmentRepository $units)
    {
        foreach($request->model as $data)
        {
            if(! $upr = $units->getById($data['id']) )
            {
                $units->save($data);
            }
            else
            {
                $last_update = $data['updated_at'];
                if($upr->updated_at < $last_update){
                    $units->update($data, $upr->id);
                }
            }
        }
        return $request->all();
    }
}
