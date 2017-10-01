<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use \Revlv\Settings\AccountCodes\AccountCodeRepository;

class AccountController extends ApiController
{
    protected $model;

    public function index(Request $request, AccountCodeRepository $model)
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
     * @param  AccountCodeRepository $model    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, AccountCodeRepository $model)
    {
        foreach($request->model as $data)
        {
            $model->save($data);
        }
        return $request->all();
    }
}
