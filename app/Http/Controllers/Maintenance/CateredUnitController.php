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
            if(! $upr = $model->getById($data['id']) )
            {
                $model->save($data);
            }
            else
            {
                $last_update = $data['updated_at'];
                if($upr->updated_at < $last_update){
                    $model->update($data, $upr->id);
                }
            }
        }
        return $request->all();
    }
}
