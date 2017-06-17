<?php

namespace Revlv\Procurements\Canvassing;

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
            ->addColumn('id', function ($data) {
                $route  =  route( 'procurements.canvassing.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->id .'</a>';
            })
            ->editColumn('canvass_rfq', function($data){
                $route  =  route( 'procurements.noa.show',[$data->canvass_id] );
                return ' <a  href="'.$route.'" > '. ucfirst($data->canvass_rfq) .'</a>';
            })
            ->rawColumns(['id', 'canvass_rfq'])
            ->make(true);
    }
}