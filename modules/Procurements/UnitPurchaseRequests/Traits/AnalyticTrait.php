<?php

namespace Revlv\Procurements\UnitPurchaseRequests\Traits;

use Illuminate\Http\Request;
use DB;
use Datatables;

trait AnalyticTrait
{

    /**
     * [getDatatable description]
     *
     * @param  [int]    $company_id ['company id ']
     * @return [type]               [description]
     */
    public function getProgramAnalytics($search = null)
    {
        $model  =   $this->model;

        $model  =   $model->select([
            DB::raw("count(unit_purchase_requests.id) as upr_count"),
            DB::raw("count(unit_purchase_requests.completed_at) as completed_count"),
            DB::raw("sum(unit_purchase_requests.total_amount) as total_abc"),
            DB::raw("sum(purchase_orders.bid_amount) as total_bid"),
            DB::raw("( sum(unit_purchase_requests.total_amount) - sum(purchase_orders.bid_amount)) as total_residual"),
            DB::raw(" avg(unit_purchase_requests.days) as avg_days"),
            DB::raw(" avg( unit_purchase_requests.days - 43 ) as avg_delays"),
            'procurement_centers.programs',
        ]);

        $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'unit_purchase_requests.place_of_delivery');

        $model  =   $model->groupBy([
            'procurement_centers.programs',
        ]);

        return $model->get();
    }

    /**
     * [getDatatable description]
     *
     * @param  [int]    $company_id ['company id ']
     * @return [type]               [description]
     */
    public function getUprCenters($program)
    {
        $model  =   $this->model;

        $model  =   $model->select([
            DB::raw("count(unit_purchase_requests.id) as upr_count"),
            DB::raw("count(unit_purchase_requests.completed_at) as completed_count"),
            DB::raw("sum(unit_purchase_requests.total_amount) as total_abc"),
            DB::raw("sum(purchase_orders.bid_amount) as total_bid"),
            DB::raw("( sum(unit_purchase_requests.total_amount) - sum(purchase_orders.bid_amount)) as total_residual"),
            DB::raw(" avg(unit_purchase_requests.days) as avg_days"),
            DB::raw(" avg( unit_purchase_requests.days - 43 ) as avg_delays"),
            'procurement_centers.programs',
            'procurement_centers.name',
        ]);

        $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'unit_purchase_requests.place_of_delivery');

        $model  =   $model->where('procurement_centers.programs', '=', $program);

        $model  =   $model->groupBy([
            'procurement_centers.programs',
            'procurement_centers.name',
        ]);

        return $model->get();
    }

    /**
     * [getDatatable description]
     *
     * @param  [int]    $company_id ['company id ']
     * @return [type]               [description]
     */
    public function getUprs($name, $programs)
    {
        $model  =   $this->model;

        $model  =   $model->select([
            DB::raw("count(unit_purchase_requests.id) as upr_count"),
            DB::raw("count(unit_purchase_requests.completed_at) as completed_count"),
            DB::raw("sum(unit_purchase_requests.total_amount) as total_abc"),
            DB::raw("sum(purchase_orders.bid_amount) as total_bid"),
            DB::raw("( sum(unit_purchase_requests.total_amount) - sum(purchase_orders.bid_amount)) as total_residual"),
            DB::raw(" avg(unit_purchase_requests.days) as avg_days"),
            DB::raw(" avg( unit_purchase_requests.days - 43 ) as avg_delays"),

            'procurement_centers.name',
            'procurement_centers.programs',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.state',
        ]);

        $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'unit_purchase_requests.place_of_delivery');

        $model  =   $model->where('procurement_centers.name', '=', $name);
        $model  =   $model->where('procurement_centers.programs', '=', $programs);

        $model  =   $model->groupBy([
            'procurement_centers.name',
            'procurement_centers.programs',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.state',
        ]);

        return $model->get();
    }


}