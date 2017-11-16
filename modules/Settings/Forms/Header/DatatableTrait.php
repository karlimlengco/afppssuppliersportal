<?php

namespace Revlv\Settings\Forms\Header;

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
            'form_headers.id',
            'form_headers.unit_id',
            'form_headers.content',
            'catered_units.short_code'
            ]);


        if(!\Sentinel::getUser()->hasRole('Admin') )
        {
            $user = \Sentinel::getUser();
            if($user->unit_id)
            {
              $model  =   $model->where('form_headers.unit_id','=', $user->unit_id);
            }
        }

        $model  =   $model->leftJoin('catered_units', 'catered_units.id', 'form_headers.unit_id');


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
                $route  =  route( 'maintenance.forms-headers.edit',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->short_code .'</a>';
            })
            ->editColumn('content', function ($data) {
                return  $data->content;
            })
            ->rawColumns(['short_code'])
            ->make(true);
    }
}