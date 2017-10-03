<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use Revlv\Biddings\PreProc\PreProcRepository;

class PreprocController extends ApiController
{
    protected $model;

    public function index(Request $request, PreProcRepository $model)
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
     * @param  PreProcRepository $model    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, PreProcRepository $model)
    {
        foreach($request->model as $data)
        {
            $model->save($data);
        }
        return $request->all();
    }
}
