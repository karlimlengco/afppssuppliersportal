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
        if($type != 'alternative')
        {
            $model  =   $model->select([
                // DB::raw("count(unit_purchase_requests.id) as upr_count"),
                DB::raw(" (select count(unit_purchase_requests.id) from unit_purchase_requests left join procurement_centers as pc on unit_purchase_requests.procurement_office  = pc.id where mode_of_procurement  = 'public_bidding' and programs = procurement_centers.programs ) as upr_count "),


                DB::raw("(select SUM(CASE
                     WHEN unit_purchase_requests.delay_count != 0 and unit_purchase_requests.state != 'completed' THEN 1
                     ELSE 0
                   END)
                    from unit_purchase_requests
                    left join procurement_centers as pc
                    on unit_purchase_requests.procurement_office  = pc.id
                    where mode_of_procurement  = 'public_bidding'
                    and programs = procurement_centers.programs )
                    as delay_count"),

                DB::raw("(select count(unit_purchase_requests.completed_at)
                    from unit_purchase_requests
                    left join procurement_centers as pc
                    on unit_purchase_requests.procurement_office  = pc.id
                    where mode_of_procurement  = 'public_bidding'
                    and programs = procurement_centers.programs ) as completed_count"),

                DB::raw("
                    (select count(unit_purchase_requests.id) from unit_purchase_requests left join procurement_centers as pc on unit_purchase_requests.procurement_office  = pc.id where mode_of_procurement  = 'public_bidding' and programs = procurement_centers.programs ) -
                    (select count(unit_purchase_requests.completed_at)
                    from unit_purchase_requests
                    left join procurement_centers as pc
                    on unit_purchase_requests.procurement_office  = pc.id
                    where mode_of_procurement  = 'public_bidding'
                    and programs = procurement_centers.programs )
                    as ongoing_count"),

                DB::raw("(select sum(unit_purchase_requests.total_amount)
                    from unit_purchase_requests
                    left join procurement_centers as pc
                    on unit_purchase_requests.procurement_office  = pc.id
                    where mode_of_procurement  = 'public_bidding'
                    and programs = procurement_centers.programs ) as total_abc"),

                DB::raw("(select sum(po.bid_amount)
                    from unit_purchase_requests
                    left join procurement_centers as pc
                    on unit_purchase_requests.procurement_office  = pc.id
                    left join purchase_orders as po
                    on unit_purchase_requests.id  = po.upr_id
                    where mode_of_procurement  = 'public_bidding'
                    and programs = procurement_centers.programs ) as total_bid"),

                DB::raw("( (select sum(unit_purchase_requests.total_amount)
                    from unit_purchase_requests
                    left join procurement_centers as pc
                    on unit_purchase_requests.procurement_office  = pc.id
                    where mode_of_procurement  = 'public_bidding'
                    and programs = procurement_centers.programs )
                    -
                    (select sum(po.bid_amount)
                                        from unit_purchase_requests
                                        left join procurement_centers as pc
                                        on unit_purchase_requests.procurement_office  = pc.id
                                        left join purchase_orders as po
                                        on unit_purchase_requests.id  = po.upr_id
                                        where mode_of_procurement  = 'public_bidding'
                                        and programs = procurement_centers.programs )
                    ) as total_residual"),
                // DB::raw("sum(purchase_orders.bid_amount) as total_bid"),
                // DB::raw("( sum(unit_purchase_requests.total_amount) - sum(purchase_orders.bid_amount)) as total_residual"),
                DB::raw(" avg(unit_purchase_requests.days) as avg_days"),
                DB::raw(" avg( unit_purchase_requests.days - 43 ) as avg_delays"),
                'procurement_centers.programs',
            ]);
        }else
        {

            $model  =   $model->select([
                // DB::raw("count(unit_purchase_requests.id) as upr_count"),
                DB::raw("
                    (select count(unit_purchase_requests.id)
                    from unit_purchase_requests
                    left join procurement_centers as pc
                    on unit_purchase_requests.procurement_office  = pc.id
                    where mode_of_procurement  != 'public_bidding'
                    and programs = procurement_centers.programs )
                    as upr_count"),


                DB::raw("(select SUM(CASE
                     WHEN unit_purchase_requests.delay_count != 0 and unit_purchase_requests.state != 'completed' THEN 1
                     ELSE 0
                   END)
                    from unit_purchase_requests
                    left join procurement_centers as pc
                    on unit_purchase_requests.procurement_office  = pc.id
                    where mode_of_procurement  != 'public_bidding'
                    and programs = procurement_centers.programs )
                    as delay_count"),

                // DB::raw("count(unit_purchase_requests.delay_count) -
                //     count(unit_purchase_requests.completed_at)
                //     as delay_count"),

                // DB::raw("count(unit_purchase_requests.completed_at) as completed_count"),
                DB::raw("(select count(unit_purchase_requests.completed_at)
                    from unit_purchase_requests
                    left join procurement_centers as pc
                    on unit_purchase_requests.procurement_office  = pc.id
                    where mode_of_procurement  != 'public_bidding'
                    and programs = procurement_centers.programs ) as completed_count"),


                DB::raw("
                    (select count(unit_purchase_requests.id)
                    from unit_purchase_requests
                    left join procurement_centers as pc
                    on unit_purchase_requests.procurement_office  = pc.id
                    where mode_of_procurement  != 'public_bidding'
                    and programs = procurement_centers.programs ) -
                    (select count(unit_purchase_requests.completed_at)
                    from unit_purchase_requests
                    left join procurement_centers as pc
                    on unit_purchase_requests.procurement_office  = pc.id
                    where mode_of_procurement  != 'public_bidding'
                    and programs = procurement_centers.programs )
                    as ongoing_count"),

                DB::raw("(select sum(unit_purchase_requests.total_amount)
                    from unit_purchase_requests
                    left join procurement_centers as pc
                    on unit_purchase_requests.procurement_office  = pc.id
                    where mode_of_procurement  != 'public_bidding'
                    and programs = procurement_centers.programs ) as total_abc"),
                // DB::raw("sum(unit_purchase_requests.total_amount) as total_abc"),

                DB::raw("(select sum(po.bid_amount)
                    from unit_purchase_requests
                    left join procurement_centers as pc
                    on unit_purchase_requests.procurement_office  = pc.id
                    left join purchase_orders as po
                    on unit_purchase_requests.id  = po.upr_id
                    where mode_of_procurement  != 'public_bidding'
                    and programs = procurement_centers.programs ) as total_bid"),
                // DB::raw("sum(purchase_orders.bid_amount) as total_bid"),

                DB::raw("( (select sum(unit_purchase_requests.total_amount)
                    from unit_purchase_requests
                    left join procurement_centers as pc
                    on unit_purchase_requests.procurement_office  = pc.id
                    where mode_of_procurement  != 'public_bidding'
                    and programs = procurement_centers.programs )
                    -
                    (select sum(po.bid_amount)
                                        from unit_purchase_requests
                                        left join procurement_centers as pc
                                        on unit_purchase_requests.procurement_office  = pc.id
                                        left join purchase_orders as po
                                        on unit_purchase_requests.id  = po.upr_id
                                        where mode_of_procurement  != 'public_bidding'
                                        and programs = procurement_centers.programs )
                    ) as total_residual"),

                DB::raw(" avg(unit_purchase_requests.days) as avg_days"),
                DB::raw(" avg( unit_purchase_requests.days - 43 ) as avg_delays"),
                'procurement_centers.programs',
            ]);
        }

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
