<?php

namespace Revlv\Events;

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

        $model  =   $model->where('user_id','=', \Sentinel::getUser()->id);

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
            ->addColumn('event', function ($data) {
                $route  =  route( 'procurements.unit-purchase-requests.show',[$data->model_id] );
                return ' <a  href="'.$route.'" > '. $data->event .'</a>';
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->format('dHi\H M y');
            })
            ->rawColumns(['event', 'created_at'])
            ->make(true);
    }
}