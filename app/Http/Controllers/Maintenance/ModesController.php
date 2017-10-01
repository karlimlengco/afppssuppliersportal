<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use \Revlv\Settings\ModeOfProcurements\ModeOfProcurementRepository;

class ModesController extends ApiController
{
    protected $units;

    public function index(Request $request, ModeOfProcurementRepository $units)
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
     * @param  ModeOfProcurementRepository $units    [description]
     * @return [type]                  [description]
     */
    public function listAll(Request $request, ModeOfProcurementRepository $units)
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
     * @param  ModeOfProcurementRepository $units    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, ModeOfProcurementRepository $units)
    {
        foreach($request->model as $data)
        {
            $units->save($data);
        }
        return $request->all();
    }
}
