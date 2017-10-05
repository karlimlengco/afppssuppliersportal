<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use \Revlv\Procurements\NoticeToProceed\NTPRepository;

class NTPController extends ApiController
{
    protected $model;

    public function index(Request $request, NTPRepository $model)
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
     * @param  NTPRepository $model    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, NTPRepository $model)
    {
        foreach($request->model as $data)
        {
            if(! $upr = $model->getById($data['id']) )
            {
                $model->save($data);
            }
            else
            {
                $last_update = $data['updated_at'];
                if($upr->updated_at < $last_update){
                    $model->update($data, $upr->id);
                }
            }
        }
        return $request->all();
    }
}
