<?php

namespace Revlv\Procurements\InspectionAndAcceptance;

use Illuminate\Http\Request;
use DB;
use Datatables;

trait DatatableTrait
{

    /**
     * [getDatatable description]
     *
     * @param  [int]    $company_id ['company id ']
     * @return [type]               [description]
     */
    public function getDatatable()
    {
        $model  =   $this->model;

        $model  =   $model->orderBy('created_at', 'desc');

        return $this->dataTable($model->get());
    }

    /**
     * [dataTable description]
     *
     * @param  [type] $model [description]
     * @return [type]        [description]
     */
    public function dataTable($model)
    {
        return Datatables::of($model)
            ->addColumn('delivery_number', function ($data) {
                $route  =  route( 'procurements.inspection-and-acceptance.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->delivery_number .'</a>';
            })
            ->rawColumns(['delivery_number'])
            ->make(true);
    }
}