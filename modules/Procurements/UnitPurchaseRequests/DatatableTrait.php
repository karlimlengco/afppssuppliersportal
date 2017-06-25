<?php

namespace Revlv\Procurements\UnitPurchaseRequests;

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
            'unit_purchase_requests.id',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.ref_number',
            'unit_purchase_requests.date_prepared',
            'unit_purchase_requests.created_at',
            'unit_purchase_requests.total_amount',
            'unit_purchase_requests.status',
            'unit_purchase_requests.state',
            'mode_of_procurements.name as type',
            DB::raw("CONCAT(users.first_name,' ', users.surname) AS full_name"),
            DB::raw("COUNT(unit_purchase_request_items.id) as item_count")
        ]);

        $model  =   $model->leftJoin('users', 'users.id', '=', 'unit_purchase_requests.prepared_by');
        $model  =   $model->leftJoin('mode_of_procurements', 'mode_of_procurements.id', '=', 'unit_purchase_requests.mode_of_procurement');
        $model  =   $model->leftJoin('unit_purchase_request_items', 'unit_purchase_request_items.upr_id', '=', 'unit_purchase_requests.id');

        $model  =   $model->groupBy([
            'unit_purchase_requests.id',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.date_prepared',
            'unit_purchase_requests.ref_number',
            'unit_purchase_requests.total_amount',
            'unit_purchase_requests.status',
            'unit_purchase_requests.state',
            'unit_purchase_requests.created_at',
            'users.first_name',
            'users.surname',
            'mode_of_procurements.name',
        ]);
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
            ->addColumn('upr_number', function ($data) {
                $route  =  route( 'procurements.unit-purchase-requests.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->upr_number .'</a>';
            })
            ->editColumn('total_amount', function($data){
                return formatPrice($data->total_amount);
            })
            ->editColumn('status', function($data){
                return ucfirst($data->status);
            })
            ->editColumn('state', function($data){
                return ucfirst($data->state);
            })
            ->rawColumns(['upr_number'])
            ->make(true);
    }
}