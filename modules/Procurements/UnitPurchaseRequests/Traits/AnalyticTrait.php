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
    public function getProgramAnalytics($search = null, $type = null)
    {
        $model  =   $this->model;

        $model  =   $model->select([
            DB::raw("count(unit_purchase_requests.id) as upr_count"),

            DB::raw("count(unit_purchase_requests.delay_count) as delay_count"),

            DB::raw("count(unit_purchase_requests.completed_at) as completed_count"),

            DB::raw(" count(unit_purchase_requests.id) -( count(unit_purchase_requests.completed_at) )as ongoing_count"),

            DB::raw("sum(unit_purchase_requests.total_amount) as total_abc"),
            DB::raw("sum(purchase_orders.bid_amount) as total_bid"),
            DB::raw("( sum(unit_purchase_requests.total_amount) - sum(purchase_orders.bid_amount)) as total_residual"),
            DB::raw(" avg(unit_purchase_requests.days) as avg_days"),
            DB::raw(" avg( unit_purchase_requests.days - 43 ) as avg_delays"),
            'procurement_centers.programs',
        ]);

        $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->crossJoin('procurement_centers', 'procurement_centers.id', '=', 'unit_purchase_requests.procurement_office');

        if($type != 'alternative')
        {
            $model  =   $model->where('mode_of_procurement', '=', 'public_bidding');
        }
        else
        {
            $model  =   $model->where('mode_of_procurement', '!=', 'public_bidding');
        }

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
    public function getUprCenters($program, $type = null)
    {
        $model  =   $this->model;

        $model  =   $model->select([
            DB::raw("count(unit_purchase_requests.id) as upr_count"),

            DB::raw("count(unit_purchase_requests.delay_count)  - count(unit_purchase_requests.completed_at) as delay_count"),

            DB::raw("count(unit_purchase_requests.completed_at) as completed_count"),

            DB::raw("
                count(unit_purchase_requests.id) -
                ( count(unit_purchase_requests.completed_at) )
                as ongoing_count"),

            DB::raw("sum(unit_purchase_requests.total_amount) as total_abc"),
            DB::raw("sum(purchase_orders.bid_amount) as total_bid"),
            DB::raw("( sum(unit_purchase_requests.total_amount) - sum(purchase_orders.bid_amount)) as total_residual"),
            DB::raw(" avg(unit_purchase_requests.days) as avg_days"),
            DB::raw(" avg( unit_purchase_requests.days - 43 ) as avg_delays"),
            'procurement_centers.programs',
            'procurement_centers.name',
        ]);

        $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'unit_purchase_requests.procurement_office');

        $model  =   $model->where('procurement_centers.programs', '=', $program);

        if($type != 'alternative')
        {
            $model  =   $model->where('mode_of_procurement', '=', 'public_bidding');
        }
        else
        {
            $model  =   $model->where('mode_of_procurement', '!=', 'public_bidding');
        }

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
    public function getUnits($name, $programs, $type=null)
    {
        $model  =   $this->model;

        $model  =   $model->select([
            DB::raw("count(unit_purchase_requests.id) as upr_count"),

            DB::raw("count(unit_purchase_requests.delay_count) - count(unit_purchase_requests.completed_at) as delay_count"),

            DB::raw("count(unit_purchase_requests.completed_at) as completed_count"),

            DB::raw("
                count(unit_purchase_requests.id) -
                ( count(unit_purchase_requests.completed_at) )
                as ongoing_count"),

            DB::raw("sum(unit_purchase_requests.total_amount) as total_abc"),
            DB::raw("sum(purchase_orders.bid_amount) as total_bid"),
            DB::raw("(sum(unit_purchase_requests.total_amount) - sum(purchase_orders.bid_amount)) as total_residual"),
            DB::raw(" avg(unit_purchase_requests.days) as avg_days"),
            DB::raw(" avg( unit_purchase_requests.days - 43 ) as avg_delays"),
            // DB::raw(" unit_purchase_requests.delay_count as delay"),
            'procurement_centers.name',
            'procurement_centers.programs',
            'catered_units.short_code',
        ]);

        $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'unit_purchase_requests.procurement_office');
        $model  =   $model->leftJoin('catered_units', 'catered_units.id', '=', 'unit_purchase_requests.units');

        $model  =   $model->where('procurement_centers.name', '=', $name);
        $model  =   $model->where('procurement_centers.programs', '=', $programs);

        if($type != 'alternative')
        {
            $model  =   $model->where('mode_of_procurement', '=', 'public_bidding');
        }
        else
        {
            $model  =   $model->where('mode_of_procurement', '!=', 'public_bidding');
        }

        $model  =   $model->groupBy([
            'procurement_centers.name',
            'procurement_centers.programs',
            // 'unit_purchase_requests.delay_count',
            'catered_units.short_code',
        ]);

        return $model->get();
    }

    /**
     * [getDatatable description]
     *
     * @param  [int]    $company_id ['company id ']
     * @return [type]               [description]
     */
    public function getUprs($name, $programs, $type=null)
    {
        $model  =   $this->model;

        $model  =   $model->select([
            DB::raw("count(unit_purchase_requests.id) as upr_count"),

            DB::raw("count(unit_purchase_requests.delay_count)- count(unit_purchase_requests.completed_at) as delay_count"),

            DB::raw("count(unit_purchase_requests.completed_at) as completed_count"),

            DB::raw("
                count(unit_purchase_requests.id) -
                ( count(unit_purchase_requests.completed_at) )
                as ongoing_count"),

            DB::raw("sum(unit_purchase_requests.total_amount) as total_abc"),
            DB::raw("sum(purchase_orders.bid_amount) as total_bid"),
            DB::raw("(sum(unit_purchase_requests.total_amount) - sum(purchase_orders.bid_amount)) as total_residual"),
            DB::raw(" avg(unit_purchase_requests.days) as avg_days"),
            DB::raw(" avg( unit_purchase_requests.days - 43 ) as avg_delays"),
            DB::raw(" unit_purchase_requests.delay_count as delay"),
            'procurement_centers.name',
            'procurement_centers.programs',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.project_name',
            'unit_purchase_requests.id',
            'unit_purchase_requests.status',
            'catered_units.short_code',
        ]);

        $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('catered_units', 'catered_units.id', '=', 'unit_purchase_requests.units');
        $model  =   $model->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'unit_purchase_requests.procurement_office');

        $model  =   $model->where('procurement_centers.name', '=', $name);
        $model  =   $model->where('catered_units.short_code', '=', $programs);
        // $model  =   $model->where('procurement_centers.programs', '=', $programs);

        if($type != 'alternative')
        {
            $model  =   $model->where('mode_of_procurement', '=', 'public_bidding');
        }
        else
        {
            $model  =   $model->where('mode_of_procurement', '!=', 'public_bidding');
        }

        $model  =   $model->groupBy([
            'procurement_centers.name',
            'procurement_centers.programs',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.delay_count',
            'unit_purchase_requests.status',
            'catered_units.short_code',
            'unit_purchase_requests.project_name',
            'unit_purchase_requests.id',
        ]);

        return $model->get();
    }


}