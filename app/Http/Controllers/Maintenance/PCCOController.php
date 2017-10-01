<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use \Revlv\Settings\ProcurementCenters\ProcurementCenterRepository;

class PCCOController extends ApiController
{
    protected $model;

    public function index(Request $request, ProcurementCenterRepository $model)
    {
        $result = $model->all();

        return response()->json([
            'data' => $result,
        ]);
    }

    /**
     * [store description]
     *
     * @param  Request        $request [description]
     * @param  ProcurementCenterRepository $model    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, ProcurementCenterRepository $model)
    {
        foreach($request->model as $data)
        {
            $model->save($data);
        }
        return $request->all();
    }
}
