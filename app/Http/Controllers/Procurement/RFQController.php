<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use \Revlv\Procurements\BlankRequestForQuotation\BlankRFQRepository;

class RFQController extends ApiController
{
    protected $model;

    public function index(Request $request, BlankRFQRepository $model)
    {
        $unitList = $model->all(['upr']);

        return response()->json([
            'data' => $unitList,
        ]);
    }

    /**
     * [store description]
     *
     * @param  Request        $request [description]
     * @param  BlankRFQRepository $model    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, BlankRFQRepository $model)
    {
        foreach($request->model as $data)
        {
            $model->save($data);
        }
        return $request->all();
    }
}
