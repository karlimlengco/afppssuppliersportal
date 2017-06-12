<?php

namespace Revlv\Procurements\PhilGepsPosting;

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

        $model->orderBy('created_at', 'desc');

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
            ->addColumn('philgeps_number', function ($data) {
                $route  =  route( 'procurements.philgeps-posting.edit',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->philgeps_number .'</a>';
            })
            ->rawColumns(['philgeps_number'])
            ->make(true);
    }
}