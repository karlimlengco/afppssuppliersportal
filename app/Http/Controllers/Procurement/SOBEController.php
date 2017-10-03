<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use Revlv\Biddings\BidOpening\BidOpeningRepository;

class SOBEController extends ApiController
{
    protected $model;

    public function index(Request $request, BidOpeningRepository $model)
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
     * @param  BidOpeningRepository $model    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, BidOpeningRepository $model)
    {
        foreach($request->model as $data)
        {
            $model->save($data);
        }
        return $request->all();
    }
}
