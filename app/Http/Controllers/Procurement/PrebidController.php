<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use Revlv\Biddings\PreBid\PreBidRepository;

class PrebidController extends ApiController
{
    protected $model;

    public function index(Request $request, PreBidRepository $model)
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
     * @param  PreBidRepository $model    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, PreBidRepository $model)
    {
        foreach($request->model as $data)
        {
            $model->save($data);
        }
        return $request->all();
    }
}
