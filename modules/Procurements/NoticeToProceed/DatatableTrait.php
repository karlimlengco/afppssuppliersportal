<?php

namespace Revlv\Procurements\NoticeToProceed;

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
            'notice_to_proceed.prepared_date',
            'notice_to_proceed.id',
            'request_for_quotations.rfq_number',
            'request_for_quotations.upr_number',
            'purchase_orders.po_number',
            'purchase_orders.purchase_date',
            'purchase_orders.mfo_released_date',
            'purchase_orders.funding_released_date',
            'purchase_orders.bid_amount',
            'purchase_orders.status',
            'suppliers.name'
        ]);

        $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.id', '=', 'notice_to_proceed.rfq_id');

        $model  =   $model->leftJoin('rfq_proponents', 'rfq_proponents.id', '=', 'notice_to_proceed.proponent_id');
        $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.id', '=', 'notice_to_proceed.po_id');

        $model  =   $model->leftJoin('suppliers', 'suppliers.id', '=', 'rfq_proponents.proponents');

        $model->orderBy('notice_to_proceed.created_at', 'desc');

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
                $route  =  route( 'procurements.ntp.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->rfq_number .'</a>';
            })
            ->editColumn('bid_amount', function ($data) {
                return formatPrice($data->bid_amount  );
            })
            ->rawColumns(['rfq_number'])
            ->make(true);
    }
}