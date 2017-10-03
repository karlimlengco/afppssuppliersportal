<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use Revlv\Biddings\InvitationToBid\InvitationToBidRepository;

class ITBController extends ApiController
{
    protected $model;

    public function index(Request $request, InvitationToBidRepository $model)
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
     * @param  InvitationToBidRepository $model    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, InvitationToBidRepository $model)
    {
        foreach($request->model as $data)
        {
            $model->save($data);
        }
        return $request->all();
    }
}
