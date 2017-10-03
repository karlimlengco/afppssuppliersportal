<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use Revlv\Biddings\DocumentAcceptance\DocumentAcceptanceRepository;

class DocumentAcceptanceController extends ApiController
{
    protected $model;

    public function index(Request $request, DocumentAcceptanceRepository $model)
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
     * @param  DocumentAcceptanceRepository $model    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, DocumentAcceptanceRepository $model)
    {
        foreach($request->model as $data)
        {
            $model->save($data);
        }
        return $request->all();
    }
}
