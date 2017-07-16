<?php

namespace Revlv\Biddings\PreBid;

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
            ->addColumn('upr_number', function ($data) {
                $route  =  route( 'biddings.pre-bids.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->upr_number .'</a>';
            })
            ->editColumn('status', function($data){
                return ucfirst($data->status);
            })
            ->rawColumns(['upr_number'])
            ->make(true);
    }
}