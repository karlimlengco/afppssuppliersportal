<?php

namespace Revlv\Library\Catalogs;

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
            ->addColumn('name', function ($data) {
                $route  =  route( 'library.catalogs.edit',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->name .'</a>';
            })
            ->editColumn('description', function($data){
                return $data->description;
            })
            ->rawColumns(['name'])
            ->make(true);
    }
}