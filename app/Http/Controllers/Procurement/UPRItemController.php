<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use \Revlv\Procurements\Items\ItemRepository;

class UPRItemController extends ApiController
{
    protected $model;

    public function index(Request $request, ItemRepository $model)
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
     * @param  ItemRepository $model    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, ItemRepository $model)
    {
        foreach($request->model as $data)
        {
            $model->save($data);
        }
        return $request->all();
    }
}
