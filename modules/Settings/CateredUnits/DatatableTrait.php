<?php

namespace Revlv\Settings\CateredUnits;

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
        $model  =   $model->select([
            'catered_units.id',
            'catered_units.*',
            'procurement_centers.name as procurement_name'
        ]);

        $model  =   $model->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'catered_units.pcco_id');

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
            ->addColumn('short_code', function ($data) {
                $route  =  route( 'maintenance.catered-units.edit',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->short_code .'</a>';
            })
            ->rawColumns(['short_code'])
            ->make(true);
    }
}