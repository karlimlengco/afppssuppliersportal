<?php

namespace Revlv\Procurements\DeliveryOrder;

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
    public function getDatatable($type = 'alternative')
    {
        $model  =   $this->model;

        if($type == 'alternative')
        {
            $model  =   $model->whereNotNull('rfq_number');
        }
        else
        {
            $model  =   $model->whereNull('rfq_number');
        }

        $model  =   $model->orderBy('created_at', 'desc');

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
            ->addColumn('rfq_number', function ($data) {
                $route  =  route( 'procurements.delivery-orders.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->rfq_number .'</a>';
            })
            ->editColumn('upr_number', function ($data) {
                $route  =  route( 'biddings.delivery-orders.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->upr_number .'</a>';
            })
            ->editColumn('dtc_rfq_number', function ($data) {
                $route  =  route( 'procurements.delivery-to-coa.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->rfq_number .'</a>';
            })
            ->editColumn('inspect_rfq_number', function ($data) {
                $route  =  route( 'procurements.delivered-inspections.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->rfq_number .'</a>';
            })
            ->rawColumns(['rfq_number','dtc_rfq_number', 'inspect_rfq_number','upr_number'])
            ->make(true);
    }
}