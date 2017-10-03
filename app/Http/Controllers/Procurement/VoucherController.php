<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use \Revlv\Procurements\Vouchers\VoucherRepository;

class VoucherController extends ApiController
{
    protected $model;

    public function index(Request $request, VoucherRepository $model)
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
     * @param  VoucherRepository $model    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, VoucherRepository $model)
    {
        foreach($request->model as $data)
        {
            $model->save($data);
        }
        return $request->all();
    }
}
