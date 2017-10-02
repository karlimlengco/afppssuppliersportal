<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use \Revlv\Procurements\DeliveryOrder\Items\ItemRepository;

class NODItemController extends ApiController
{
    protected $model;

    public function index(Request $request, ItemRepository $model)
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
