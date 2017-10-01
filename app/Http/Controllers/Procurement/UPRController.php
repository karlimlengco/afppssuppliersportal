<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use \Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestRepository;

class UPRController extends ApiController
{
    protected $model;

    public function index(Request $request, UnitPurchaseRequestRepository $model)
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
     * @param  UnitPurchaseRequestRepository $model    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, UnitPurchaseRequestRepository $model)
    {
        foreach($request->model as $data)
        {
            $model->save($data);
        }
        return $request->all();
    }
}
