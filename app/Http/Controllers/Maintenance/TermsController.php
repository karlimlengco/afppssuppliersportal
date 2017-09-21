<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use \Revlv\Settings\PaymentTerms\PaymentTermRepository;

class TermsController extends ApiController
{
    protected $units;

    public function index(Request $request, PaymentTermRepository $units)
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
     * @param  PaymentTermRepository $units    [description]
     * @return [type]                  [description]
     */
    public function listAll(Request $request, PaymentTermRepository $units)
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
     * @param  PaymentTermRepository $units    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, PaymentTermRepository $units)
    {
        foreach($request->model as $data)
        {
            $units->save($data);
        }
        return $request->all();
    }
}
