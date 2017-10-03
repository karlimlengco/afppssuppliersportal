<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use \Revlv\Procurements\DeliveryInspection\Issues\IssueRepository;

class DIIRIssueController extends ApiController
{
    protected $model;

    public function index(Request $request, IssueRepository $model)
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
     * @param  IssueRepository $model    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, IssueRepository $model)
    {
        foreach($request->model as $data)
        {
            $model->save($data);
        }
        return $request->all();
    }
}
