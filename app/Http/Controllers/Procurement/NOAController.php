<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use \Revlv\Procurements\NoticeOfAward\NOARepository;

class NOAController extends ApiController
{
    protected $model;

    public function index(Request $request, NOARepository $model)
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
     * @param  NOARepository $model    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, NOARepository $model)
    {
        foreach($request->model as $data)
        {
            $model->save($data);
        }
        return $request->all();
    }
}
