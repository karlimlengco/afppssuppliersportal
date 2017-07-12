<?php

namespace Revlv\Biddings\RequestForBid;

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
            'request_for_bidding.*',
            'bacsec.name as bacsec'
        ]);

        $model  =   $model->leftJoin('bacsec', 'bacsec.id', 'request_for_bidding.bac_id');

        $model->orderBy('request_for_bidding.created_at', 'desc');

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
            ->addColumn('rfb_number', function ($data) {
                $route  =  route( 'biddings.request-for-bids.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->rfb_number .'</a>';
            })
            ->editColumn('status', function($data){
                return ucfirst($data->status);
            })
            ->rawColumns(['rfb_number'])
            ->make(true);
    }
}