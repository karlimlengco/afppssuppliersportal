<?php

namespace Revlv\Procurements\PurchaseOrder;

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
                $route  =  route( 'procurements.purchase-orders.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->rfq_number .'</a>';
            })
            ->addColumn('po_number', function ($data) {
                $route  =  route( 'biddings.purchase-orders.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->po_number .'</a>';
            })
            ->editColumn('ntp_rfq_number', function ($data) {
                $route  =  route( 'procurements.ntp.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->rfq_number .'</a>';
            })
            ->editColumn('bid_amount', function ($data) {
                return formatPrice($data->bid_amount);
            })
            ->rawColumns(['rfq_number','ntp_rfq_number', 'po_number'])
            ->make(true);
    }
}