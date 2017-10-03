<?php

namespace App\Http\Controllers\Procurement;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use JWTAuth;
use \Revlv\Procurements\InspectionAndAcceptance\Invoices\InvoiceRepository;

class IAARInvoiceController extends ApiController
{
    protected $model;

    public function index(Request $request, InvoiceRepository $model)
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
     * @param  InvoiceRepository $model    [description]
     * @return [type]                  [description]
     */
    public function store(Request $request, InvoiceRepository $model)
    {
        foreach($request->model as $data)
        {
            $model->save($data);
        }
        return $request->all();
    }
}
