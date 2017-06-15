<?php

namespace Revlv\Procurements\RFQProponents;

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
            ->addColumn('proponents', function ($data) {
                $route  =  route( 'procurements.rfq-proponents.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->proponents .'</a>';
            })
            ->rawColumns(['proponents'])
            ->make(true);
    }
}