<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use Revlv\Sentinel\Permissions\PermissionRepository;

class PermissionController extends ApiController
{
    protected $permissions;

    public function index(Request $request, PermissionRepository $permissions)
    {
        $unitList = $permissions->all();

        return response()->json([
            'data' => $unitList,
        ]);
    }

    /**
     * [store description]
     *
     * @param  Request        $request [description]
     * @param  PermissionRepository $permissions    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, PermissionRepository $permissions)
    {
        foreach($request->model as $data)
        {
            $model = $permissions->save($data);
        }
        return $request->all();
    }
}
