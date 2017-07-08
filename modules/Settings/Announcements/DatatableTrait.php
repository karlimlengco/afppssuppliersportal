<?php

namespace Revlv\Settings\Announcements;

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
            ->addColumn('title', function ($data) {
                $route  =  route( 'maintenance.announcements.edit',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->title .'</a>';
            })
            ->rawColumns(['title'])
            ->make(true);
    }
}