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

            // DB::raw("count(unit_purchase_requests.delay_count) as delay_count"),

            DB::raw("IFNULL( SUM(CASE
             WHEN 5 * (DATEDIFF(NOW(), unit_purchase_requests.next_due) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.next_due) + WEEKDAY(NOW()) + 1, 1) > 0 and unit_purchase_requests.state != 'completed' THEN 1
             ELSE 0
           END), 0)  as delay_count"),

            DB::raw("count(unit_purchase_requests.completed_at) as completed_count"),

            // DB::raw(" count(unit_purchase_requests.id) -( count(unit_purchase_requests.completed_at) )as ongoing_count"),

            DB::raw(" SUM(CASE
             WHEN unit_purchase_requests.state != 'cancelled' THEN 1
             ELSE 0
           END) -
                ( count(unit_purchase_requests.completed_at) )
                as ongoing_count"),

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
        $model  =   $model->where('unit_purchase_requests.status', '!=', 'draft');

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
    public function getUprCenters($program, $type = null, $request = null)
    {
        $date_from = "";
        $date    = \Carbon\Carbon::now();
        $yearto    = $date->format('Y');
        $yearfrom    = $date->format('Y');
        $date_to = $date->format('Y-m-d');

        if($request != null && $request->has('date_from') != null)
        {
            $date_from  =   $request->get('date_from');
            $yearfrom  =   \Carbon\Carbon::createFromFormat('Y-m-d', $date_from)->format('Y');
        }

        if($request != null && $request->has('date_to') != null)
        {
            $date_to  =   $request->get('date_to');
            $yearto  =   \Carbon\Carbon::createFromFormat('Y-m-d', $date_to)->format('Y');
        }

        if($request != null && $request->has('date_to') &&  $request->get('date_from') == null)
        {
            $date_from  =   $date_to;
            $yearfrom  =   \Carbon\Carbon::createFromFormat('Y-m-d', $date_from)->format('Y');
        }

        $model  =   $this->model;

        $model  =   $model->select([
            DB::raw("count(unit_purchase_requests.id) as upr_count"),

            DB::raw("IFNULL( SUM(CASE
             WHEN 5 * (DATEDIFF(NOW(), unit_purchase_requests.next_due) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.next_due) + WEEKDAY(NOW()) + 1, 1) > 0 and unit_purchase_requests.state != 'completed' and unit_purchase_requests.next_due <  NOW()  AND unit_purchase_requests.state != 'cancelled'  THEN 1
             ELSE 0
           END),0) as delay_count"),
            // DB::raw("count(unit_purchase_requests.delay_count)  - count(unit_purchase_requests.completed_at) as delay_count"),

            DB::raw("count(unit_purchase_requests.completed_at) as completed_count"),

            DB::raw(" SUM(CASE
             WHEN unit_purchase_requests.state != 'cancelled' THEN 1
             ELSE 0
           END) -
                ( count(unit_purchase_requests.completed_at) )
                as ongoing_count"),


            DB::raw(" SUM(CASE
              WHEN unit_purchase_requests.state = 'cancelled' THEN 1
              ELSE 0
            END)  cancelled_count"),



            // DB::raw("
            //     count(unit_purchase_requests.id) -
            //     ( count(unit_purchase_requests.completed_at) )
            //     as ongoing_count"),

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

        if(!\Sentinel::getUser()->hasRole('Admin') )
        {

            $center =   0;
            $user = \Sentinel::getUser();
            if($user->units)
            {
                if($user->units->centers)
                {
                    $center =   $user->units->centers->id;
                }
            }

            $model  =   $model->where('unit_purchase_requests.procurement_office','=', $center);

        }
        $model  =   $model->where('unit_purchase_requests.status', '!=', 'draft');

        $model  =   $model->groupBy([
            'procurement_centers.programs',
            'procurement_centers.name',
        ]);

        $model  = $model->whereRaw("YEAR(unit_purchase_requests.date_prepared) <= '$yearto' AND YEAR(unit_purchase_requests.date_prepared) >= '$yearfrom' ");

        $model  =   $model->orderBy('delay_count','desc');

        return $model->get();
    }

    /**
     * [getDatatable description]
     *
     * @param  [int]    $company_id ['company id ']
     * @return [type]               [description]
     */
    public function getUnits($name, $programs, $type=null, $request = null)
    {

        $date_from = "";
        $date    = \Carbon\Carbon::now();
        $yearto    = $date->format('Y');
        $yearfrom    = $date->format('Y');
        $date_to = $date->format('Y-m-d');

        if($request != null && $request->has('date_from') != null)
        {
            $date_from  =   $request->get('date_from');
            $yearfrom  =   \Carbon\Carbon::createFromFormat('Y-m-d', $date_from)->format('Y');
        }

        if($request != null && $request->has('date_to') != null)
        {
            $date_to  =   $request->get('date_to');
            $yearto  =   \Carbon\Carbon::createFromFormat('Y-m-d', $date_to)->format('Y');
        }

        if($request != null && $request->has('date_to') &&  $request->get('date_from') == null)
        {
            $date_from  =   $date_to;
            $yearfrom  =   \Carbon\Carbon::createFromFormat('Y-m-d', $date_from)->format('Y');
        }
        $model  =   $this->model;

        $model  =   $model->select([
            DB::raw("count(unit_purchase_requests.id) as upr_count"),

            // DB::raw("count(unit_purchase_requests.delay_count) - count(unit_purchase_requests.completed_at) as delay_count"),
            DB::raw("IFNULL( SUM(CASE
             WHEN 5 * (DATEDIFF(NOW(), unit_purchase_requests.next_due) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.next_due) + WEEKDAY(NOW()) + 1, 1) > 0 and unit_purchase_requests.state != 'completed' and unit_purchase_requests.next_due <  NOW()  AND unit_purchase_requests.state != 'cancelled' THEN 1
             ELSE 0
           END),0) as delay_count"),

            DB::raw("count(unit_purchase_requests.completed_at) as completed_count"),

            // DB::raw("
            //     count(unit_purchase_requests.id) -
            //     ( count(unit_purchase_requests.completed_at) )
            //     as ongoing_count"),

            DB::raw(" SUM(CASE
             WHEN unit_purchase_requests.state != 'cancelled' THEN 1
             ELSE 0
           END) -
                ( count(unit_purchase_requests.completed_at) )
                as ongoing_count"),


            DB::raw(" SUM(CASE
              WHEN unit_purchase_requests.state = 'cancelled' THEN 1
              ELSE 0
            END)  cancelled_count"),

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


        if(!\Sentinel::getUser()->hasRole('Admin') )
        {
            $center =   0;
            $user = \Sentinel::getUser();
            if($user->units)
            {
                if($user->units->centers)
                {
                    $center =   $user->units->centers->id;
                }
            }

            $model  =   $model->where('unit_purchase_requests.procurement_office','=', $center);
        }
        $model  =   $model->where('unit_purchase_requests.status', '!=', 'draft');

        $model  = $model->whereRaw("YEAR(unit_purchase_requests.date_prepared) <= '$yearto' AND YEAR(unit_purchase_requests.date_prepared) >= '$yearfrom' ");

        $model  =   $model->orderBy('delay_count','desc');
        $model  =   $model->orderBy('ongoing_count','asc');

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
    public function getUprs($name, $programs, $type=null, $request = null)
    {
        $date_from = "";
        $date    = \Carbon\Carbon::now();
        $yearto    = $date->format('Y');
        $yearfrom    = $date->format('Y');
        $date_to = $date->format('Y-m-d');

        if($request != null && $request->has('date_from') != null)
        {
            $date_from  =   $request->get('date_from');
            $yearfrom  =   \Carbon\Carbon::createFromFormat('Y-m-d', $date_from)->format('Y');
        }

        if($request != null && $request->has('date_to') != null)
        {
            $date_to  =   $request->get('date_to');
            $yearto  =   \Carbon\Carbon::createFromFormat('Y-m-d', $date_to)->format('Y');
        }

        if($request != null && $request->has('date_to') &&  $request->get('date_from') == null)
        {
            $date_from  =   $date_to;
            $yearfrom  =   \Carbon\Carbon::createFromFormat('Y-m-d', $date_from)->format('Y');
        }
        $model  =   $this->model;

        $model  =   $model->select([
            DB::raw("count(unit_purchase_requests.id) as upr_count"),

            DB::raw("IFNULL( SUM(CASE
             WHEN 5 * (DATEDIFF(NOW(), unit_purchase_requests.next_due) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.next_due) + WEEKDAY(NOW()) + 1, 1) > 0 and unit_purchase_requests.state != 'completed' and unit_purchase_requests.next_due <  NOW()  AND unit_purchase_requests.state != 'cancelled' THEN 1
             ELSE 0
           END),0) as delay_count"),

            DB::raw("count(unit_purchase_requests.completed_at) as completed_count"),

            // DB::raw("
            //     count(unit_purchase_requests.id) -
            //     ( count(unit_purchase_requests.completed_at) )
            //     as ongoing_count"),

            DB::raw(" SUM(CASE
             WHEN unit_purchase_requests.state != 'cancelled' THEN 1
             ELSE 0
           END) -
                ( count(unit_purchase_requests.completed_at) )
                as ongoing_count"),

            DB::raw("sum(unit_purchase_requests.total_amount) as total_abc"),
            DB::raw("sum(purchase_orders.bid_amount) as total_bid"),
            DB::raw("(sum(unit_purchase_requests.total_amount) - sum(purchase_orders.bid_amount)) as total_residual"),
            // DB::raw(" avg(unit_purchase_requests.days) as avg_days"),
            DB::raw("5 * (DATEDIFF(vouchers.preaudit_date, unit_purchase_requests.date_prepared) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.date_prepared) + WEEKDAY(vouchers.preaudit_date) + 1, 1) as avg_days"),
            DB::raw(" avg( unit_purchase_requests.days - 43 ) as avg_delays"),
            // DB::raw(" unit_purchase_requests.delay_count as delay"),
            'procurement_centers.name',
            'procurement_centers.programs',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.project_name',
            'unit_purchase_requests.id',
            'unit_purchase_requests.status',
            'unit_purchase_requests.last_remarks',
            'unit_purchase_requests.last_action',
            'unit_purchase_requests.next_due',
            'catered_units.short_code',

            // DB::raw("(select count(*) from holidays where holiday_date >= unit_purchase_requests.created_at and holiday_date <= NOW()) as holidays"),
            // DB::raw("datediff(NOW(), unit_purchase_requests.next_due ) as delay"),
            // DB::raw("5 * (DATEDIFF(unit_purchase_requests.next_due, NOW()) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.next_due) + WEEKDAY(NOW()) + 1, 1) as delay")
            DB::raw("5 * (DATEDIFF(NOW(), unit_purchase_requests.next_due) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.next_due) + WEEKDAY(NOW()) + 1, 1) as delay")
        ]);
        $model  =   $model->leftJoin('vouchers', 'vouchers.upr_id', '=', 'unit_purchase_requests.id');
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
        $model  =   $model->where('unit_purchase_requests.status', '!=', 'draft');


        if(!\Sentinel::getUser()->hasRole('Admin') )
        {

            $center =   0;
            $user = \Sentinel::getUser();
            if($user->units)
            {
                if($user->units->centers)
                {
                    $center =   $user->units->centers->id;
                }
            }

            $model  =   $model->where('unit_purchase_requests.procurement_office','=', $center);
        }

        $model  = $model->whereRaw("YEAR(unit_purchase_requests.date_prepared) <= '$yearto' AND YEAR(unit_purchase_requests.date_prepared) >= '$yearfrom' ");
        $model  =   $model->orderBy('delay_count','desc');
        $model  =   $model->orderBy('ongoing_count','desc');
        $model  =   $model->orderBy('completed_count','desc');

        $model  =   $model->groupBy([
            'procurement_centers.name',
            'unit_purchase_requests.next_due',
            'procurement_centers.programs',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.next_due',
            'unit_purchase_requests.delay_count',
            'unit_purchase_requests.status',
            'catered_units.short_code',
            'unit_purchase_requests.last_remarks',
            'unit_purchase_requests.last_action',
            'unit_purchase_requests.project_name',
            'unit_purchase_requests.id',
            'unit_purchase_requests.date_prepared',
            'vouchers.preaudit_date',
        ]);

        return $model->get();
    }

    /**
     * [getPSRUprCenters description]
     *
     * @param  [type] $program [description]
     * @param  [type] $type    [description]
     * @return [type]          [description]
     */
    public function getPSRUprCenters($program, $type = null, $request = null)
    {


        $model  =   $this->model;

        $model  =   $model->select([
            'procurement_centers.name',
            'procurement_centers.programs',
            DB::raw("COUNT(unit_purchase_requests.id) AS upr"),
            DB::raw("COUNT(request_for_quotations.id) AS rfq"),
            DB::raw("COUNT(pre_proc.id) AS preproc"),
            DB::raw("COUNT(invitation_for_quotation.id) AS ispq"),
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

        $model  =   $model->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'unit_purchase_requests.procurement_office');

        $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('philgeps_posting', 'philgeps_posting.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('ispq_quotations', 'ispq_quotations.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('canvassing', 'canvassing.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('notice_of_awards', 'notice_of_awards.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('invitation_for_quotation', 'invitation_for_quotation.id', '=', 'ispq_quotations.ispq_id');
        $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('notice_to_proceed', 'notice_to_proceed.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('delivery_orders', 'delivery_orders.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('inspection_acceptance_report', 'inspection_acceptance_report.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('delivery_inspection', 'delivery_inspection.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('vouchers', 'vouchers.upr_id', '=', 'unit_purchase_requests.id');


        $model  =   $model->leftJoin('document_acceptance', 'document_acceptance.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('pre_proc', 'pre_proc.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('invitation_to_bid', 'invitation_to_bid.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('pre_bid_conferences', 'pre_bid_conferences.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('bid_opening', 'bid_opening.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('post_qualification', 'post_qualification.upr_id', '=', 'unit_purchase_requests.id');

        $model  =   $model->where('procurement_centers.programs', '=', $program);

        if($type != 'psr-alternative')
        {
            $model  =   $model->where('mode_of_procurement', '=', 'public_bidding');
        }
        else
        {
            $model  =   $model->where('mode_of_procurement', '!=', 'public_bidding');
        }
        $model  =   $model->where('unit_purchase_requests.status', '!=', 'draft');

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
    public function getPSRUnit($name, $programs, $type=null)
    {
        $model  =   $this->model;

        $model  =   $model->select([
            'procurement_centers.name',
            'procurement_centers.programs',
            'catered_units.short_code',
            DB::raw("COUNT(unit_purchase_requests.id) AS upr"),
            DB::raw("COUNT(request_for_quotations.id) AS rfq"),
            DB::raw("COUNT(pre_proc.id) AS preproc"),
            DB::raw("COUNT(invitation_for_quotation.id) AS ispq"),
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

        $model  =   $model->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'unit_purchase_requests.procurement_office');

        $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('philgeps_posting', 'philgeps_posting.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('ispq_quotations', 'ispq_quotations.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('canvassing', 'canvassing.upr_id', '=', 'unit_purchase_requests.id');
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
        $model  =   $model->leftJoin('pre_proc', 'pre_proc.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('pre_bid_conferences', 'pre_bid_conferences.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('bid_opening', 'bid_opening.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('post_qualification', 'post_qualification.upr_id', '=', 'unit_purchase_requests.id');


        $model  =   $model->where('procurement_centers.name', '=', $name);
        $model  =   $model->where('procurement_centers.programs', '=', $programs);
        $model  =   $model->leftJoin('catered_units', 'catered_units.id', '=', 'unit_purchase_requests.units');

        if($type != 'psr-alternative')
        {
            $model  =   $model->where('mode_of_procurement', '=', 'public_bidding');
        }
        else
        {
            $model  =   $model->where('mode_of_procurement', '!=', 'public_bidding');
        }
        $model  =   $model->where('unit_purchase_requests.status', '!=', 'draft');

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
    public function getPSRUprs($name, $programs, $type=null)
    {
        $model  =   $this->model;

        $model  =   $model->select([
            'unit_purchase_requests.id',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.project_name',
            'procurement_centers.name',
            'procurement_centers.programs',
            'catered_units.short_code',
            DB::raw("COUNT(unit_purchase_requests.id) AS upr"),
            DB::raw("COUNT(request_for_quotations.id) AS rfq"),
            DB::raw("COUNT(pre_proc.id) AS preproc"),
            DB::raw("COUNT(invitation_for_quotation.id) AS ispq"),
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

        $model  =   $model->leftJoin('catered_units', 'catered_units.id', '=', 'unit_purchase_requests.units');
        $model  =   $model->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'unit_purchase_requests.procurement_office');

        $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('philgeps_posting', 'philgeps_posting.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('ispq_quotations', 'ispq_quotations.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('canvassing', 'canvassing.upr_id', '=', 'unit_purchase_requests.id');
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
        $model  =   $model->leftJoin('pre_proc', 'pre_proc.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('pre_bid_conferences', 'pre_bid_conferences.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('bid_opening', 'bid_opening.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('post_qualification', 'post_qualification.upr_id', '=', 'unit_purchase_requests.id');

        $model  =   $model->where('procurement_centers.name', '=', $name);
        $model  =   $model->where('catered_units.short_code', '=', $programs);

        if($type != 'psr-alternative')
        {
            $model  =   $model->where('mode_of_procurement', '=', 'public_bidding');
        }
        else
        {
            $model  =   $model->where('mode_of_procurement', '!=', 'public_bidding');
        }
        $model  =   $model->where('unit_purchase_requests.status', '!=', 'draft');

        $model  =   $model->groupBy([
            'procurement_centers.name',
            'procurement_centers.programs',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.project_name',
            'unit_purchase_requests.id',
            'catered_units.short_code',
        ]);

        return $model->get();
    }
}