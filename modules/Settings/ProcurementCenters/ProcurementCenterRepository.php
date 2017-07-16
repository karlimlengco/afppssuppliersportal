<?php

namespace Revlv\Settings\ProcurementCenters;

use DB;
use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class ProcurementCenterRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ProcurementCenterEloquent::class;
    }

    /**
     * [findByName description]
     *
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function findByName($name)
    {
        $model  =   $this->model;

        $model  =   $model->where('name', 'LIKE', "%$name%");

        return $model->first();
    }

    /**
     * [getDatatable description]
     *
     * @param  [int]    $company_id ['company id ']
     * @return [type]               [description]
     */
    public function getPrograms($search = null, $type = null)
    {
        $model  =   $this->model;

        $model  =   $model->select([
            DB::raw("count(unit_purchase_requests.id) as upr_count"),
            DB::raw("count(unit_purchase_requests.delay_count) as delay_count"),
            DB::raw("count(unit_purchase_requests.completed_at) as completed_count"),
            DB::raw(" ( count(unit_purchase_requests.id) - count(unit_purchase_requests.completed_at) ) - count(unit_purchase_requests.delay_count) as ongoing_count"),
            DB::raw("sum(unit_purchase_requests.total_amount) as total_abc"),
            DB::raw("sum(purchase_orders.bid_amount) as total_bid"),
            DB::raw("( sum(unit_purchase_requests.total_amount) - sum(purchase_orders.bid_amount)) as total_residual"),
            DB::raw(" avg(unit_purchase_requests.days) as avg_days"),
            DB::raw(" avg( unit_purchase_requests.days - 43 ) as avg_delays"),
            'procurement_centers.programs',
        ]);

        $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.procurement_office', '=', 'procurement_centers.id');

        $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id');

        // if($type != 'alternative')
        // {
        //     $model  =   $model->where('mode_of_procurement', '=', 'public_bidding');
        // }
        // else
        // {
        //     $model  =   $model->where('mode_of_procurement', '!=', 'public_bidding');
        // }

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
    public function getCenters($program, $type = null)
    {
        $model  =   $this->model;

        $model  =   $model->select([
            DB::raw("count(unit_purchase_requests.id) as upr_count"),
            DB::raw("count(unit_purchase_requests.delay_count) as delay_count"),
            DB::raw("count(unit_purchase_requests.completed_at) as completed_count"),
            DB::raw(" ( count(unit_purchase_requests.id) - count(unit_purchase_requests.completed_at) ) - count(unit_purchase_requests.delay_count) as ongoing_count"),
            DB::raw("sum(unit_purchase_requests.total_amount) as total_abc"),
            DB::raw("sum(purchase_orders.bid_amount) as total_bid"),
            DB::raw("( sum(unit_purchase_requests.total_amount) - sum(purchase_orders.bid_amount)) as total_residual"),
            DB::raw(" avg(unit_purchase_requests.days) as avg_days"),
            DB::raw(" avg( unit_purchase_requests.days - 43 ) as avg_delays"),
            'procurement_centers.programs',
            'procurement_centers.name',
        ]);

        $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.procurement_office', '=', 'procurement_centers.id');

        $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id');

        $model  =   $model->where('procurement_centers.programs', '=', $program);
        // if($type != 'alternative')
        // {
        //     $model  =   $model->where('mode_of_procurement', '=', 'public_bidding');
        // }
        // else
        // {
        //     $model  =   $model->where('mode_of_procurement', '!=', 'public_bidding');
        // }

        $model  =   $model->groupBy([
            'procurement_centers.programs',
            'procurement_centers.name',
        ]);

        return $model->get();
    }
}
