<?php

namespace Revlv\Settings\Suppliers;

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
    public function getDatatable($status = 'accepted')
    {
        $model  =   $this->model;

        $model  =   $model->whereStatus($status);

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
            ->addColumn('name', function ($data) {
                $route  =  route( 'settings.suppliers.edit',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->name .'</a>';
            })
            ->editColumn('is_blocked', function ($data) {
                return ($data->is_blocked == 1) ? "Blocked" : "";
            })
            ->rawColumns(['name'])
            ->make(true);
    }
}