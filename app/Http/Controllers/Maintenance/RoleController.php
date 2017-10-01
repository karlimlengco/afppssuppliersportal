<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use Revlv\Sentinel\Roles\RoleRepository;

class RoleController extends ApiController
{
    protected $model;

    public function index(Request $request, RoleRepository $model)
    {
        $unitList = $model->all();

        return response()->json([
            'data' => $unitList,
        ]);
    }

    /**
     * [store description]
     *
     * @param  Request        $request [description]
     * @param  RoleRepository $model    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, RoleRepository $model)
    {
        foreach($request->model as $data)
        {
            $data['permissions'] = json_decode($data['permissions']);
            $data['permissions'] = (array) $data['permissions'];
            $model->save($data);
        }
        return $request->all();
    }
}
