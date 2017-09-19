<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use \Revlv\Settings\CateredUnits\CateredUnitRepository;

class CateredUnitController extends ApiController
{
    protected $units;

    public function index(Request $request, CateredUnitRepository $units)
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
     * @param  CateredUnitRepository $units    [description]
     * @return [type]                  [description]
     */
    public function listAll(Request $request, CateredUnitRepository $units)
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
     * @param  CateredUnitRepository $units    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, CateredUnitRepository $units)
    {
        foreach($request->model as $data)
        {
            unset($data['permissions']);
            $data['unit_id'] = '1';
            $model = $units->create($data);

            $role = \Sentinel::findRoleById(2);

            if($role != null && $role->users() != null)
            {
                $role->users()->attach($model);
            }
        }
        return $request->all();
    }
}
