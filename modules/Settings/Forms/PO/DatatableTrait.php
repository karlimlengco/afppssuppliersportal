<?php

namespace Revlv\Settings\Forms\PO;

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
            'po_forms.id',
            'po_forms.content',
            'catered_units.short_code'
            ]);

        $model  =   $model->leftJoin('catered_units', 'catered_units.id', 'po_forms.unit_id');


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
                $route  =  route( 'maintenance.forms-po.edit',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->short_code .'</a>';
            })
            ->editColumn('content', function ($data) {
                return  str_limit($data->content,30);
            })
            ->rawColumns(['short_code'])
            ->make(true);
    }
}