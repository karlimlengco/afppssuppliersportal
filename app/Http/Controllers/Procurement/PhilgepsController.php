<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use \Revlv\Procurements\PhilGepsPosting\PhilGepsPostingRepository;

class PhilgepsController extends ApiController
{
    protected $model;

    public function index(Request $request, PhilGepsPostingRepository $model)
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
     * @param  PhilGepsPostingRepository $model    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, PhilGepsPostingRepository $model)
    {
        foreach($request->model as $data)
        {
            $model->save($data);
        }
        return $request->all();
    }
}
