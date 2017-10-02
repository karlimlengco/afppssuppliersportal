<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use \Revlv\Settings\Suppliers\SupplierRepository;

class SupplierController extends ApiController
{
    protected $units;

    public function index(Request $request, SupplierRepository $units)
    {
        $unitList = $units->all();

        return response()->json([
            'data' => $unitList,
        ]);
    }

    /**
     * [listAll description]
     *
     * @param  Request        $request [description]
     * @param  SupplierRepository $units    [description]
     * @return [type]                  [description]
     */
    public function listAll(Request $request, SupplierRepository $units)
    {
        $units = $units->getAllAdmins();
        return response()->json([
            'data' => $units,
        ]);
    }

    /**
     * [store description]
     *
     * @param  Request        $request [description]
     * @param  SupplierRepository $units    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, SupplierRepository $units)
    {
        foreach($request->model as $data)
        {
            $model = $units->save($data);
        }
        return $request->all();
    }
}
