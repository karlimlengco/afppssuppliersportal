<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use Revlv\Biddings\BidDocs\BidDocsRepository;

class BidsController extends ApiController
{
    protected $model;

    public function index(Request $request, BidDocsRepository $model)
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
     * @param  BidDocsRepository $model    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, BidDocsRepository $model)
    {
        foreach($request->model as $data)
        {
            $model->save($data);
        }
        return $request->all();
    }
}
