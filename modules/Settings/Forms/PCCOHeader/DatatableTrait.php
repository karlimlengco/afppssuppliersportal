<?php

namespace Revlv\Settings\Forms\PCCOHeader;

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
            'pcco_headers.pcco_id',
            'pcco_headers.id',
            'pcco_headers.content',
            'procurement_centers.name'
            ]);


        if(!\Sentinel::getUser()->hasRole('Admin') )
        {

            $center =   0;
            $user = \Sentinel::getUser();
            if($user->units)
            {
                if($user->units->centers)
                {
                    $center =   $user->units->centers->id;
                }
            }
            $model  =   $model->where('pcco_headers.pcco_id','=', $center);
        }

        $model  =   $model->leftJoin('procurement_centers', 'procurement_centers.id', 'pcco_headers.pcco_id');


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
                $route  =  route( 'maintenance.forms-pcco-headers.edit',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->name .'</a>';
            })
            ->editColumn('content', function ($data) {
                return  $data->content;
            })
            ->rawColumns(['name'])
            ->make(true);
    }
}