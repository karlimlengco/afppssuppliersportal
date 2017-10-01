<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use \Revlv\Settings\Signatories\SignatoryRepository;

class SignatoryController extends ApiController
{
    protected $model;

    public function index(Request $request, SignatoryRepository $model)
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
     * @param  SignatoryRepository $model    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, SignatoryRepository $model)
    {
        foreach($request->model as $data)
        {
            $model->save($data);
        }
        return $request->all();
    }
}
