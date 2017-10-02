<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use \Revlv\Procurements\RFQProponents\RFQProponentRepository;

class RFQProponentController extends ApiController
{
    protected $model;

    public function index(Request $request, RFQProponentRepository $model)
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
     * @param  RFQProponentRepository $model    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, RFQProponentRepository $model)
    {
        foreach($request->model as $data)
        {
            $model->save($data);
        }
        return $request->all();
    }
}
