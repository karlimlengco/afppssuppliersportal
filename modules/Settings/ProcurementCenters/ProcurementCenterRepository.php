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
     * [findByRFQId description]
     *
     * @param  [type] $rfq [description]
     * @return [type]      [description]
     */
    public function getById($id)
    {
        $model  =    $this->model;

        $model  =   $model->where('id', '=', $id);

        return $model->first();
    }

    /**
     * [getPSRPrograms description]
     *
     * @param  [type] $type    [description]
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    public function getPSRPrograms($type=null, $request)
    {
        $model  =   $this->model;

        $model  =   $model->select([
            'procurement_centers.programs',
            DB::raw("COUNT(unit_purchase_requests.id) AS upr"),
            DB::raw("COUNT(request_for_quotations.id) AS rfq"),
            DB::raw("COUNT(invitation_for_quotation.id) AS ispq"),
            DB::raw("COUNT(pre_proc.id) AS preproc"),
            DB::raw("COUNT(philgeps_posting.id) AS philgeps"),
            DB::raw("COUNT(canvassing.id) AS canvass"),
            DB::raw("COUNT(notice_of_awards.id) AS noa"),
            DB::raw("COUNT(purchase_orders.id) AS po"),
            DB::raw("COUNT(notice_to_proceed.id) AS ntp"),
            DB::raw("COUNT(delivery_orders.id) AS do"),
            DB::raw("COUNT(inspection_acceptance_report.id) AS tiac"),
            DB::raw("COUNT(delivery_inspection.id) AS diir"),
            DB::raw("COUNT(vouchers.id) AS voucher"),
            DB::raw("COUNT(document_acceptance.id) as doc"),
            DB::raw("COUNT(invitation_to_bid.id) as itb"),
            DB::raw("COUNT(pre_bid_conferences.id) as prebid"),
            DB::raw("COUNT(bid_opening.id) as bidop"),
            DB::raw("COUNT(post_qualification.id) as pq"),
        ]);




        // $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.procurement_office', '=', 'procurement_centers.id');
        if($type != 'psr-alternative')
        {
            $model  =   $model->leftJoin('unit_purchase_requests', function ($join) {
                            $join->on('procurement_centers.id', '=', 'unit_purchase_requests.procurement_office')
                                 ->where('unit_purchase_requests.mode_of_procurement', '=', 'public_bidding');
                        });
        }
        else
        {
            $model  =   $model->leftJoin('unit_purchase_requests', function ($join) {
                            $join->on('procurement_centers.id', '=', 'unit_purchase_requests.procurement_office')
                                 ->where('unit_purchase_requests.mode_of_procurement', '!=', 'public_bidding');
                        });
        }



        $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('philgeps_posting', 'philgeps_posting.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('ispq_quotations', 'ispq_quotations.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('canvassing', 'canvassing.upr_id', '=', 'unit_purchase_requests.id');

        $model  =   $model->leftJoin('pre_proc', 'pre_proc.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('notice_of_awards', 'notice_of_awards.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('invitation_for_quotation', 'invitation_for_quotation.id', '=', 'ispq_quotations.ispq_id');
        $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('notice_to_proceed', 'notice_to_proceed.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('delivery_orders', 'delivery_orders.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('inspection_acceptance_report', 'inspection_acceptance_report.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('delivery_inspection', 'delivery_inspection.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('vouchers', 'vouchers.upr_id', '=', 'unit_purchase_requests.id');


        $model  =   $model->leftJoin('document_acceptance', 'document_acceptance.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('invitation_to_bid', 'invitation_to_bid.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('pre_bid_conferences', 'pre_bid_conferences.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('bid_opening', 'bid_opening.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('post_qualification', 'post_qualification.upr_id', '=', 'unit_purchase_requests.id');


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
    public function getPrograms($search = null, $type = null, $request)
    {
        $date_from = "";
        $date    =  \Carbon\Carbon::now();
        $year    =  $date->format('Y');
        $date_to = $date->format('Y-m-d');

        if($request->has('date_from') != null)
        {
            $date_from  =   $request->get('date_from');
        }

        if($request->has('date_to') != null)
        {
            $date_to  =   $request->get('date_to');
        }

        if($request->has('date_to') &&  $request->get('date_from') == null)
        {
            $date_from  =   $date_to;
        }

        $model  =   $this->model;
        if(!\Sentinel::getUser()->hasRole('Admin') )
        {

            $unit_id    =   \Sentinel::getUser()->unit_id;
            if($type != 'alternative')
            {
                $model  =   $model->select([
                    // DB::raw("count(unit_purchase_requests.id) as upr_count"),
                    DB::raw(" (select count(unit_purchase_requests.id) from unit_purchase_requests left join procurement_centers as pc on unit_purchase_requests.procurement_office  = pc.id
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id where mode_of_procurement  = 'public_bidding' and programs = procurement_centers.programs  and catered_units.id = '$unit_id' and unit_purchase_requests.status != 'draft'  and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to') as upr_count "),

                    DB::raw(" (select sum(unit_attachments.amount) from unit_purchase_requests left join procurement_centers as pc on unit_purchase_requests.procurement_office  = pc.id
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id
                        LEFT JOIN unit_attachments ON unit_attachments.unit_id = catered_units.id
                        where mode_of_procurement  = 'public_bidding' and programs = procurement_centers.programs  and catered_units.id = '$unit_id' and unit_purchase_requests.status != 'draft'  and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to') as upr_count "),

 // and unit_purchase_requests.units = '$unit_id'
                    DB::raw("
                        (select count(unit_purchase_requests.id) from unit_purchase_requests left join procurement_centers as pc on unit_purchase_requests.procurement_office  = pc.id
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id where mode_of_procurement  = 'public_bidding' and programs = procurement_centers.programs and catered_units.id = '$unit_id' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to' and unit_purchase_requests.state = 'cancelled' and unit_purchase_requests.status != 'draft')
                        as cancelled_count"),

                    DB::raw(" IFNULL( (select SUM(CASE
                                 WHEN 5 * (DATEDIFF(NOW(), unit_purchase_requests.next_due) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.next_due) + WEEKDAY(NOW()) + 1, 1) > 0 and unit_purchase_requests.state != 'completed' AND unit_purchase_requests.state != 'cancelled' THEN 1
                                 ELSE 0
                       END)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id
                        where mode_of_procurement  = 'public_bidding'
                        and unit_purchase_requests.next_due <  NOW()
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and catered_units.id = '$unit_id' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to' ) ,0 )
                        as delay_count"),

                    DB::raw("(select count(unit_purchase_requests.completed_at)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id
                        where mode_of_procurement  = 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and catered_units.id = '$unit_id' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to') as completed_count"),

                    DB::raw("
                        (select count(unit_purchase_requests.id) from unit_purchase_requests left join procurement_centers as pc on unit_purchase_requests.procurement_office  = pc.id
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id where mode_of_procurement  = 'public_bidding' and programs = procurement_centers.programs and catered_units.id = '$unit_id' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.status != 'draft' and unit_purchase_requests.date_prepared <= '$date_to'  AND unit_purchase_requests.state != 'cancelled') -
                        (select count(unit_purchase_requests.completed_at)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id
                        where mode_of_procurement  = 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and catered_units.id = '$unit_id' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to'  AND unit_purchase_requests.state != 'cancelled')
                        as ongoing_count"),

                    DB::raw("(select sum(unit_purchase_requests.total_amount)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id
                        where mode_of_procurement  = 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and catered_units.id = '$unit_id' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to') as total_abc"),

                    DB::raw("(select sum(po.bid_amount)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id
                        left join purchase_orders as po
                        on unit_purchase_requests.id  = po.upr_id
                        where mode_of_procurement  = 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and catered_units.id = '$unit_id' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to') as total_bid"),

                    DB::raw("( (select sum(unit_purchase_requests.total_amount)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id
                        where mode_of_procurement  = 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and catered_units.id = '$unit_id' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to')
                        -
                        (select sum(po.bid_amount)
                                            from unit_purchase_requests
                                            left join procurement_centers as pc
                                            on unit_purchase_requests.procurement_office  = pc.id
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id
                                            left join purchase_orders as po
                                            on unit_purchase_requests.id  = po.upr_id
                                            where mode_of_procurement  = 'public_bidding'
                                            and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and catered_units.id = '$unit_id' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to')
                        ) as total_residual"),
                    // DB::raw("sum(purchase_orders.bid_amount) as total_bid"),
                    // DB::raw("( sum(unit_purchase_requests.total_amount) - sum(purchase_orders.bid_amount)) as total_residual"),
                    DB::raw(" avg(unit_purchase_requests.days) as avg_days"),
                    DB::raw(" avg( unit_purchase_requests.days - 43 ) as avg_delays"),
                    'procurement_centers.programs',
                    // 'catered_units.id',
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
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id
                        where mode_of_procurement  != 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and catered_units.id = '$unit_id' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to')
                        as upr_count"),

                    DB::raw("
                        (select sum(unit_attachments.amount)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id
                        LEFT JOIN unit_attachments ON unit_attachments.unit_id = catered_units.id
                        where mode_of_procurement  != 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and catered_units.id = '$unit_id' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to')
                        as apb_total"),


// unit_purchase_requests.units

                    DB::raw("
                        (select count(unit_purchase_requests.id)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id
                        where mode_of_procurement  != 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and catered_units.id = '$unit_id' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to' and unit_purchase_requests.state = 'cancelled')
                        as cancelled_count"),


                    DB::raw("IFNULL( (select SUM(CASE
                                 WHEN 5 * (DATEDIFF(NOW(), unit_purchase_requests.next_due) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.next_due) + WEEKDAY(NOW()) + 1, 1) > 0 and unit_purchase_requests.state != 'completed' AND unit_purchase_requests.state != 'cancelled' THEN 1
                                 ELSE 0
                       END)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id
                        where mode_of_procurement  != 'public_bidding'
                        and unit_purchase_requests.next_due <  NOW()
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and catered_units.id = '$unit_id' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to'), 0)
                        as delay_count"),

                    // DB::raw("count(unit_purchase_requests.delay_count) -
                    //     count(unit_purchase_requests.completed_at)
                    //     as delay_count"),

                    // DB::raw("count(unit_purchase_requests.completed_at) as completed_count"),
                    DB::raw("(select count(unit_purchase_requests.completed_at)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id
                        where mode_of_procurement  != 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and catered_units.id = '$unit_id' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to') as completed_count"),


                    DB::raw("
                        (select count(unit_purchase_requests.id)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id
                        where mode_of_procurement  != 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and catered_units.id = '$unit_id' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to'  AND unit_purchase_requests.state != 'cancelled') -
                        (select count(unit_purchase_requests.completed_at)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id
                        where mode_of_procurement  != 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and catered_units.id = '$unit_id' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to'  AND unit_purchase_requests.state != 'cancelled')
                        as ongoing_count"),

                    DB::raw("(select sum(unit_purchase_requests.total_amount)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id
                        where mode_of_procurement  != 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and catered_units.id = '$unit_id' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to') as total_abc"),
                    // DB::raw("sum(unit_purchase_requests.total_amount) as total_abc"),

                    DB::raw("(select sum(po.bid_amount)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id
                        left join purchase_orders as po
                        on unit_purchase_requests.id  = po.upr_id
                        where mode_of_procurement  != 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and catered_units.id = '$unit_id' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to' ) as total_bid"),
                    // DB::raw("sum(purchase_orders.bid_amount) as total_bid"),

                    DB::raw("( (select sum(unit_purchase_requests.total_amount)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id
                        where mode_of_procurement  != 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and catered_units.id = '$unit_id' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to')
                        -
                        (select sum(po.bid_amount)
                                            from unit_purchase_requests
                                            left join procurement_centers as pc
                                            on unit_purchase_requests.procurement_office  = pc.id
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id
                                            left join purchase_orders as po
                                            on unit_purchase_requests.id  = po.upr_id
                                            where mode_of_procurement  != 'public_bidding'
                                            and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and catered_units.id = '$unit_id' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to' )
                        ) as total_residual"),

                    DB::raw(" avg(unit_purchase_requests.days) as avg_days"),
                    DB::raw(" avg( unit_purchase_requests.days - 43 ) as avg_delays"),
                    'procurement_centers.programs',
                    // 'catered_units.id',
                ]);
            }
        }
        else
        {
            if($type != 'alternative')
            {
                $model  =   $model->select([
                    // DB::raw("count(unit_purchase_requests.id) as upr_count"),
                    DB::raw(" (select count(unit_purchase_requests.id) from unit_purchase_requests left join procurement_centers as pc on unit_purchase_requests.procurement_office  = pc.id where mode_of_procurement  = 'public_bidding' and unit_purchase_requests.status != 'draft' and programs = procurement_centers.programs and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to') as upr_count "),

                    DB::raw(" ( select sum(unit_attachments.amount) from unit_purchase_requests left join procurement_centers as pc on unit_purchase_requests.procurement_office  = pc.id LEFT JOIN catered_units ON catered_units.pcco_id = pc.id
                        LEFT JOIN unit_attachments ON unit_attachments.unit_id = catered_units.id where mode_of_procurement  = 'public_bidding' and unit_purchase_requests.status != 'draft' and programs = procurement_centers.programs and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to') as apb_total "),



                    DB::raw("
                        (select count(unit_purchase_requests.id) from unit_purchase_requests left join procurement_centers as pc on unit_purchase_requests.procurement_office  = pc.id where mode_of_procurement  = 'public_bidding' and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and  unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to' and unit_purchase_requests.state = 'cancelled')
                        as cancelled_count"),

                    DB::raw("IFNULL( (select SUM(CASE
                                 WHEN 5 * (DATEDIFF(NOW(), unit_purchase_requests.next_due) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.next_due) + WEEKDAY(NOW()) + 1, 1) > 0 and unit_purchase_requests.state != 'completed' AND unit_purchase_requests.state != 'cancelled' THEN 1
                                 ELSE 0
                       END)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        where mode_of_procurement  = 'public_bidding'
                        and unit_purchase_requests.next_due <  NOW()
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to'), 0)
                        as delay_count"),

                    DB::raw("(select count(unit_purchase_requests.completed_at)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        where mode_of_procurement  = 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to') as completed_count"),

                    DB::raw("
                        (select count(unit_purchase_requests.id) from unit_purchase_requests left join procurement_centers as pc on unit_purchase_requests.procurement_office  = pc.id where mode_of_procurement  = 'public_bidding' and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to'  AND unit_purchase_requests.state != 'cancelled') -
                        (select count(unit_purchase_requests.completed_at)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        where mode_of_procurement  = 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to'  AND unit_purchase_requests.state != 'cancelled')
                        as ongoing_count"),

                    DB::raw("(select sum(unit_purchase_requests.total_amount)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        where mode_of_procurement  = 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to') as total_abc"),

                    DB::raw("(select sum(po.bid_amount)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        left join purchase_orders as po
                        on unit_purchase_requests.id  = po.upr_id
                        where mode_of_procurement  = 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to') as total_bid"),

                    DB::raw("( (select sum(unit_purchase_requests.total_amount)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        where mode_of_procurement  = 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to')
                        -
                        (select sum(po.bid_amount)
                                            from unit_purchase_requests
                                            left join procurement_centers as pc
                                            on unit_purchase_requests.procurement_office  = pc.id
                                            left join purchase_orders as po
                                            on unit_purchase_requests.id  = po.upr_id
                                            where mode_of_procurement  = 'public_bidding'
                                             and unit_purchase_requests.status != 'draft' and programs = procurement_centers.programs )
                        ) as total_residual"),
                    // DB::raw("sum(purchase_orders.bid_amount) as total_bid"),
                    // DB::raw("( sum(unit_purchase_requests.total_amount) - sum(purchase_orders.bid_amount)) as total_residual"),
                    DB::raw(" avg(unit_purchase_requests.days) as avg_days"),
                    DB::raw(" avg( unit_purchase_requests.days - 43 ) as avg_delays"),
                    'procurement_centers.programs',
                    // 'catered_units.id',
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
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to')
                        as upr_count"),
                    DB::raw("
                        (select sum(unit_attachments.amount)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        LEFT JOIN catered_units ON catered_units.pcco_id = pc.id
                        LEFT JOIN unit_attachments ON unit_attachments.unit_id = catered_units.id
                        where mode_of_procurement  != 'public_bidding'
                        and unit_attachments.unit_id = unit_purchase_requests.units
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to')
                        as apb_total"),


                    DB::raw("
                        (select count(unit_purchase_requests.id)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        where mode_of_procurement  != 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to' and unit_purchase_requests.state = 'cancelled')
                        as cancelled_count"),


                    DB::raw("IFNULL( (select SUM(CASE
                                 WHEN 5 * (DATEDIFF(NOW(), unit_purchase_requests.next_due) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.next_due) + WEEKDAY(NOW()) + 1, 1) > 0 and unit_purchase_requests.state != 'completed' and unit_purchase_requests.status != 'draft' AND unit_purchase_requests.state != 'cancelled' THEN 1
                                 ELSE 0
                       END)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        where mode_of_procurement  != 'public_bidding'
                        and unit_purchase_requests.next_due <  NOW()
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to'), 0)
                        as delay_count"),

                    // IFNULL( SUM(CASE
                    //              WHEN 5 * (DATEDIFF(NOW(), unit_purchase_requests.next_due) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.next_due) + WEEKDAY(NOW()) + 1, 1) > 0 and unit_purchase_requests.state != 'completed' AND unit_purchase_requests.state != 'cancelled' THEN 1
                    //              ELSE 0
                    //            END),0) as delay_count

                    // DB::raw("count(unit_purchase_requests.delay_count) -
                    //     count(unit_purchase_requests.completed_at)
                    //     as delay_count"),

                    // DB::raw("count(unit_purchase_requests.completed_at) as completed_count"),
                    DB::raw("(select count(unit_purchase_requests.completed_at)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        where mode_of_procurement  != 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to') as completed_count"),


                    DB::raw("
                        (select count(unit_purchase_requests.id)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        where mode_of_procurement  != 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to'  AND unit_purchase_requests.state != 'cancelled') -
                        (select count(unit_purchase_requests.completed_at)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        where mode_of_procurement  != 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to'  AND unit_purchase_requests.state != 'cancelled')
                        as ongoing_count"),

                    DB::raw("(select sum(unit_purchase_requests.total_amount)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        where mode_of_procurement  != 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to') as total_abc"),
                    // DB::raw("sum(unit_purchase_requests.total_amount) as total_abc"),

                    DB::raw("(select sum(po.bid_amount)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        left join purchase_orders as po
                        on unit_purchase_requests.id  = po.upr_id
                        where mode_of_procurement  != 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to' ) as total_bid"),
                    // DB::raw("sum(purchase_orders.bid_amount) as total_bid"),

                    DB::raw("( (select sum(unit_purchase_requests.total_amount)
                        from unit_purchase_requests
                        left join procurement_centers as pc
                        on unit_purchase_requests.procurement_office  = pc.id
                        where mode_of_procurement  != 'public_bidding'
                        and programs = procurement_centers.programs and unit_purchase_requests.status != 'draft' and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to' )
                        -
                        (select sum(po.bid_amount)
                                            from unit_purchase_requests
                                            left join procurement_centers as pc
                                            on unit_purchase_requests.procurement_office  = pc.id
                                            left join purchase_orders as po
                                            on unit_purchase_requests.id  = po.upr_id
                                            where mode_of_procurement  != 'public_bidding'
                                             and unit_purchase_requests.status != 'draft' and programs = procurement_centers.programs and unit_purchase_requests.date_prepared >= '$date_from' and unit_purchase_requests.date_prepared <= '$date_to')
                        ) as total_residual"),

                    DB::raw(" avg(unit_purchase_requests.days) as avg_days"),
                    DB::raw(" avg( unit_purchase_requests.days - 43 ) as avg_delays"),
                    'procurement_centers.programs',
                    // 'catered_units.id',
                ]);
            }
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

        // if(!\Sentinel::getUser()->hasRole('Admin') )
        // {
        //     $model  =   $model->having('catered_units.id','=', \Sentinel::getUser()->unit_id);
        // }
        $model  = $model->whereNotNull('procurement_centers.id');

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
