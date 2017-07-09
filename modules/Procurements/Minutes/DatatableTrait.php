<?php

namespace Revlv\Procurements\Minutes;

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
            ->addColumn('date_opened', function ($data) {
                $route  =  route( 'procurements.minutes.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->date_opened .'</a>';
            })
            ->rawColumns(['date_opened'])
            ->make(true);
    }
}