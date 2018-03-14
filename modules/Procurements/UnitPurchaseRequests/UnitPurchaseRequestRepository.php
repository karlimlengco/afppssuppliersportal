<?php

namespace Revlv\Procurements\UnitPurchaseRequests;
use Illuminate\Http\Request;

use Revlv\BaseRepository;
use DB;
use Illuminate\Database\Eloquent\Model;
use Revlv\Procurements\UnitPurchaseRequests\Traits\PSRTrait;
use Revlv\Procurements\UnitPurchaseRequests\Traits\TransactionDaysTrait;
use Revlv\Procurements\UnitPurchaseRequests\Traits\AnalyticTrait;

class UnitPurchaseRequestRepository extends BaseRepository
{
    use  DatatableTrait, PSRTrait, TransactionDaysTrait, AnalyticTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return UnitPurchaseRequestEloquent::class;
    }

    /**
     * Return the model by its key valued pair
     *
     * @param string $id
     * @param string $value
     * @return mixed
     */
    public function listPending($id = 'id', $value = 'name')
    {

        $model =    $this->model;

        $model =    $model->whereStatus('pending');

        return $model->pluck($value, $id)->all();
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
     * [findByRFQId description]
     *
     * @param  [type] $rfq [description]
     * @return [type]      [description]
     */
    public function findByRFQId($rfq)
    {
        $model  =    $this->model;

        $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.upr_id', '=', 'unit_purchase_requests.id');

        $model  =   $model->where('request_for_quotations.id', '=', $rfq);

        return $model->first();
    }

    /**
     * [findTimelineById description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function findTimelineById($id)
    {
        $model  =    $this->model;

        $model  =   $model->select([
            'unit_purchase_requests.id',
            'unit_purchase_requests.project_name',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.ref_number',
            'unit_purchase_requests.date_processed',
            'unit_purchase_requests.state',
            'unit_purchase_requests.total_amount',
            'unit_purchase_requests.mode_of_procurement',
            'unit_purchase_requests.date_processed',
            'unit_purchase_requests.next_due',
            'unit_purchase_requests.date_processed as upr_created_at',
            'unit_purchase_requests.delay_count',
            'request_for_quotations.id as rfq_id',
            'request_for_quotations.transaction_date as rfq_created_at',
            'request_for_quotations.completed_at as rfq_completed_at',
            'request_for_quotations.days as rfq_days',
            'request_for_quotations.close_days as rfq_closed_days',
            'request_for_quotations.close_remarks as rfq_close_remarks',
            'request_for_quotations.close_action as rfq_close_action',
            'request_for_quotations.remarks as rfq_remarks',
            'request_for_quotations.action as rfq_action',
            'invitation_for_quotation.id as ispq_id',
            'invitation_for_quotation.transaction_date as ispq_transaction_date',
            'ispq_quotations.delay_count as ispq_days',
            'ispq_quotations.remarks as ispq_remarks',
            'ispq_quotations.action as ispq_action',
            'philgeps_posting.philgeps_posting as pp_completed_at',
            'philgeps_posting.days as pp_days',
            'philgeps_posting.id as pp_id',
            'philgeps_posting.remarks as pp_remarks',
            'philgeps_posting.action as pp_action',
            'canvassing.canvass_date as canvass_start_date',
            'canvassing.id as canvass_id',
            'canvassing.days as canvass_days',
            'canvassing.remarks as canvass_remarks',
            'canvassing.action as canvass_action',
            'notice_of_awards.id as noa_id',
            'notice_of_awards.days as noa_days',
            'notice_of_awards.approved_days as noa_approved_days',
            'notice_of_awards.received_days as noa_received_days',
            'notice_of_awards.remarks as noa_remarks',
            'notice_of_awards.action as noa_action',
            'notice_of_awards.approved_remarks as noa_approved_remarks',
            'notice_of_awards.approved_action as noa_approved_action',
            'notice_of_awards.received_remarks as noa_received_remarks',
            'notice_of_awards.received_action as noa_received_action',
            'notice_of_awards.awarded_date as noa_award_date',
            'notice_of_awards.accepted_date as noa_approved_date',
            'notice_of_awards.award_accepted_date as noa_award_accepted_date',
            'notice_of_awards.philgeps_posting as noa_philgeps_posting',
            'notice_of_awards.philgeps_days as noa_philgeps_days',
            'purchase_orders.id as po_id',
            'purchase_orders.days as po_days',
            'purchase_orders.remarks as po_remarks',
            'purchase_orders.action as po_action',
            'purchase_orders.purchase_date as po_create_date',
            'purchase_orders.mfo_received_date',
            'purchase_orders.funding_received_date',
            'purchase_orders.funding_days as po_fund_days',
            'purchase_orders.mfo_days as po_mfo_days',
            'purchase_orders.coa_days as po_coa_days',
            'purchase_orders.funding_remarks as po_funding_remarks',
            'purchase_orders.funding_action as po_funding_action',
            'purchase_orders.mfo_remarks as po_mfo_remarks',
            'purchase_orders.mfo_action as po_mfo_action',
            'purchase_orders.coa_remarks as po_coa_remarks',
            'purchase_orders.coa_action as po_coa_action',
            'purchase_orders.coa_approved_date',
            'purchase_orders.delivery_terms',
            'notice_to_proceed.id as ntp_id',
            'notice_to_proceed.days as ntp_days',
            'notice_to_proceed.accepted_days as ntp_accepted_days',
            'notice_to_proceed.accepted_remarks as ntp_accepted_remarks',
            'notice_to_proceed.accepted_action as ntp_accepted_action',
            'notice_to_proceed.remarks as ntp_remarks',
            'notice_to_proceed.action as ntp_action',
            'notice_to_proceed.prepared_date as ntp_date',
            'notice_to_proceed.award_accepted_date as ntp_award_date',
            'notice_to_proceed.philgeps_posting as ntp_philgeps_posting',
            'notice_to_proceed.philgeps_days as ntp_philgeps_days',
            'delivery_orders.transaction_date as dr_date',

            'delivery_orders.id as dr_id',


            DB::raw(" (select delivery_orders.id from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id order by delivery_orders.created_at desc limit 1) as dr_id "),

            DB::raw(" (select delivery_orders.days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id order by delivery_orders.created_at desc limit 1) as dr_days "),

            DB::raw(" (select delivery_orders.transaction_date from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id order by delivery_orders.created_at desc limit 1) as dr_date "),

            DB::raw(" (select delivery_orders.date_delivered_to_coa from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id order by delivery_orders.created_at desc limit 1) as dr_coa_date "),

            DB::raw(" (select delivery_orders.remarks from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id order by delivery_orders.created_at desc limit 1) as dr_remarks "),

            DB::raw(" (select delivery_orders.action from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id order by delivery_orders.created_at desc limit 1) as dr_action "),
            DB::raw(" (select delivery_orders.delivery_days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id order by delivery_orders.created_at desc limit 1) as dr_delivery_days "),
            DB::raw(" (select delivery_orders.delivery_remarks from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id order by delivery_orders.created_at desc limit 1) as dr_delivery_remarks "),
            DB::raw(" (select delivery_orders.delivery_action from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id order by delivery_orders.created_at desc limit 1) as dr_delivery_action "),
            DB::raw(" (select delivery_orders.dr_coa_days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id order by delivery_orders.created_at desc limit 1) as dr_dr_coa_days "),
            DB::raw(" (select delivery_orders.dr_coa_remarks from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id order by delivery_orders.created_at desc limit 1) as dr_dr_coa_remarks "),
            DB::raw(" (select delivery_orders.dr_coa_action from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id order by delivery_orders.created_at desc limit 1) as dr_dr_coa_action "),
            DB::raw(" (select delivery_orders.delivery_date from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id order by delivery_orders.created_at desc limit 1) as delivery_date "),

            // 'delivery_orders.days as dr_days',
            // 'delivery_orders.remarks as dr_remarks',
            // 'delivery_orders.action as dr_action',
            // 'delivery_orders.delivery_days as dr_delivery_days',
            // 'delivery_orders.delivery_remarks as dr_delivery_remarks',
            // 'delivery_orders.delivery_action as dr_delivery_action',
            // 'delivery_orders.dr_coa_days as dr_dr_coa_days',
            // 'delivery_orders.dr_coa_remarks as dr_dr_coa_remarks',
            // 'delivery_orders.dr_coa_action as dr_dr_coa_action',
            // 'delivery_orders.delivery_date',
            // 'delivery_orders.date_delivered_to_coa as dr_coa_date',

            'inspection_acceptance_report.id as tiac_id',
            'inspection_acceptance_report.accept_days as tiac_accept_days',
            'inspection_acceptance_report.accept_remarks as tiac_accept_remarks',
            'inspection_acceptance_report.accept_action as tiac_accept_action',
            'inspection_acceptance_report.days as tiac_days',
            'inspection_acceptance_report.remarks as tiac_remarks',
            'inspection_acceptance_report.action as tiac_action',
            'inspection_acceptance_report.inspection_date as dr_inspection',
            'inspection_acceptance_report.accepted_date as iar_accepted_date',
            'delivery_inspection.id as diir_id',
            'delivery_inspection.days as diir_days',
            'delivery_inspection.close_days as diir_close_days',
            'delivery_inspection.remarks as diir_remarks',
            'delivery_inspection.action as diir_action',
            'delivery_inspection.close_remarks as diir_close_remarks',
            'delivery_inspection.close_action as diir_close_action',
            'delivery_inspection.closed_date as di_close',
            'delivery_inspection.start_date as di_start',
            'vouchers.id as vou_id',
            'vouchers.days as vou_days',
            'vouchers.preaudit_days as vou_preaudit_days',
            'vouchers.preaudit_remarks as vou_preaudit_remarks',
            'vouchers.preaudit_action as vou_preaudit_action',
            'vouchers.certify_days as vou_certify_days',
            'vouchers.certify_remarks as vou_certify_remarks',
            'vouchers.certify_action as vou_certify_action',
            'vouchers.jev_days as vou_jev_days',
            'vouchers.jev_remarks as vou_jev_remarks',
            'vouchers.jev_action as vou_jev_action',
            'vouchers.approved_days as vou_approved_days',
            'vouchers.approved_remarks as vou_approved_remarks',
            'vouchers.approved_action as vou_approved_action',
            'vouchers.released_days as vou_released_days',
            'vouchers.released_remarks as vou_released_remarks',
            'vouchers.released_action as vou_released_action',
            'vouchers.received_days as vou_received_days',
            'vouchers.received_remarks as vou_received_remarks',
            'vouchers.received_action as vou_received_action',
            'vouchers.remarks as vou_remarks',
            'vouchers.action as vou_action',
            'vouchers.created_at as vou_start',
            'vouchers.transaction_date as v_transaction_date',
            'vouchers.preaudit_date as preaudit_date',
            'vouchers.certify_date as certify_date',
            'vouchers.journal_entry_date as journal_entry_date',
            'vouchers.approval_date as vou_approval_date',
            'vouchers.payment_release_date as vou_release',
            'vouchers.payment_received_date as vou_received',
            'vouchers.prepare_cheque_date as vou_prepare_cheque_date',
            'vouchers.prepare_cheque_days as vou_prepare_cheque_days',
            'vouchers.counter_sign_date as vou_counter_sign_date',
            'vouchers.counter_sign_days as vou_counter_sign_days',
            // DB::raw("(select count(*) from holidays where holiday_date >= unit_purchase_requests.created_at and holiday_date <= NOW()) as holidays"),
            // DB::raw("datediff(NOW(), unit_purchase_requests.created_at ) as days"),
            // DB::raw("5 * (DATEDIFF(NOW(), unit_purchase_requests.created_at) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.created_at) + WEEKDAY(NOW()) + 1, 1) as working_days")
        ]);

        $model  =   $model ->leftJoin('philgeps_posting', function ($q) {
                       $q->on('philgeps_posting.upr_id', '=', 'unit_purchase_requests.id')
                         ->where('philgeps_posting.status', '=', 1);
                 });
        // $model  =   $model->leftJoin('philgeps_posting', 'philgeps_posting.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('ispq_quotations', 'ispq_quotations.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('invitation_for_quotation', 'invitation_for_quotation.id', '=', 'ispq_quotations.ispq_id');
        // $model  =   $model->leftJoin('canvassing', 'canvassing.upr_id', '=', 'unit_purchase_requests.id');

        $model  =   $model ->leftJoin('canvassing', function ($q) {
                       $q->on('canvassing.upr_id', '=', 'unit_purchase_requests.id')
                         ->whereNULL('canvassing.is_failed');
                 });

        $model  =   $model->leftJoin('notice_of_awards', 'notice_of_awards.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('notice_to_proceed', 'notice_to_proceed.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('delivery_orders', 'delivery_orders.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('inspection_acceptance_report', 'inspection_acceptance_report.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('delivery_inspection', 'delivery_inspection.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('vouchers', 'vouchers.upr_id', '=', 'unit_purchase_requests.id');

        $model  =   $model->where('unit_purchase_requests.id', '=', $id);

        return $model->first();
    }

    /**
     * [getCountByYear description]
     *
     * @param  [type] $year [description]
     * @return [type]       [description]
     */
    public function getCountByYear($year)
    {

        $model  =   $this->model;

        $model  =   $model->select([
            DB::raw("count(id) as total")
        ]);

        $model  =   $model->whereYear('date_processed', '=', $year);

        return $model->first();
    }

    /**
     *
     *
     * @return [type] [description]
     */
    public function getDelays($paginate = 20, $request = null)
    {
        $user   =   \Sentinel::getUser();
        $unit_id= $user->unit_id;

        $model  =   $this->model;

        $model  =   $model->select([
            'unit_purchase_requests.id',
            'unit_purchase_requests.project_name',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.ref_number',
            'unit_purchase_requests.date_processed',
            'unit_purchase_requests.state',
            'unit_purchase_requests.total_amount',
            'unit_purchase_requests.mode_of_procurement',
            'unit_purchase_requests.date_processed',
            'unit_purchase_requests.date_processed as upr_created_at',
            'unit_purchase_requests.next_due',
            'unit_purchase_requests.next_step',
            DB::raw("(select count(*) from holidays where holiday_date >= unit_purchase_requests.date_processed and holiday_date <= NOW()) as holidays"),
            DB::raw("(SELECT COUNT(*) FROM holidays WHERE holiday_date >= unit_purchase_requests.date_processed and holiday_date <= NOW() AND DAYOFWEEK(holiday_date) < 6) as holidays2"),
            // DB::raw("5 * (DATEDIFF(NOW(), unit_purchase_requests.next_due) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.next_due) + WEEKDAY(NOW()) + 1, 1) as delay")
            DB::raw("(5 * (DATEDIFF(NOW(), unit_purchase_requests.next_due) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.next_due) + WEEKDAY(NOW()) + 1, 1)) as test"),
            // DB::raw("datediff( unit_purchase_requests.next_due, NOW()) as delay")
            DB::raw("(5 * (DATEDIFF(NOW(), unit_purchase_requests.next_due) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.next_due) + WEEKDAY(NOW()) + 1, 1) - (SELECT COUNT(*) FROM holidays WHERE holiday_date >= unit_purchase_requests.date_processed and holiday_date <= NOW() AND DAYOFWEEK(holiday_date) < 6 ) ) as delay")
        ]);

        $model  =   $model->where('state', '!=', 'completed');
        $model  =   $model->where('state', '!=', 'Terminated pass');
        $model  =   $model->where('state', '!=', 'Terminated failed');
        $model  =   $model->where('unit_purchase_requests.status', '!=', 'draft');

        $model  =   $model->groupBy([
            'unit_purchase_requests.id',
            'unit_purchase_requests.project_name',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.ref_number',
            'unit_purchase_requests.date_processed',
            'unit_purchase_requests.state',
            'unit_purchase_requests.total_amount',
            'unit_purchase_requests.mode_of_procurement',
            'unit_purchase_requests.date_processed',
            'unit_purchase_requests.date_processed',
            'unit_purchase_requests.created_at',
            'unit_purchase_requests.next_due',
            'unit_purchase_requests.next_step',
        ]);
        $model  =   $model->havingRaw("(5 * (DATEDIFF(NOW(), unit_purchase_requests.next_due) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.next_due) + WEEKDAY(NOW()) + 1, 1) - (SELECT COUNT(*) FROM holidays WHERE holiday_date >= unit_purchase_requests.date_processed and holiday_date <= NOW() AND DAYOFWEEK(holiday_date) < 6 ) ) > 0");

        $model  =   $model->whereRaw("unit_purchase_requests.next_due <  NOW() ");
        if($request && $request->has('search') && $request->search != null)
        {
            $search =   $request->search;
            $model  =   $model->where('unit_purchase_requests.upr_number','LIKE', "%$search%");
            $model  =   $model->orWhere('unit_purchase_requests.ref_number','LIKE', "%$search%");
            $model  =   $model->orWhere('unit_purchase_requests.project_name','LIKE', "%$search%");
            $model  =   $model->orWhere('unit_purchase_requests.next_step','LIKE', "%$search%");
        }

        if(!$user->hasRole('Admin'))
        {
            $model  =   $model->where('unit_purchase_requests.units','=',$unit_id);
        }

        $model  =   $model->paginate($paginate);

        return $model;
    }

    /**
     * [getInfo description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getInfo($id)
    {
        $model  =    $this->model;

        $model  =   $model->select([
            'unit_purchase_requests.terms_of_payment',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.total_amount as abc',
            'unit_purchase_requests.ref_number',
            'unit_purchase_requests.purpose',
            'unit_purchase_request_items.id as item_id',
            'unit_purchase_request_items.item_description',
            'unit_purchase_request_items.item_description',
            'unit_purchase_request_items.quantity',
            'unit_purchase_request_items.unit_measurement',
            'unit_purchase_request_items.new_account_code',
            'account_codes.new_account_code as account_code',
            'unit_purchase_request_items.unit_price',
            'unit_purchase_request_items.total_amount',
        ]);

        $model  =   $model->leftJoin('unit_purchase_request_items', 'unit_purchase_request_items.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('account_codes', 'account_codes.id', '=', 'unit_purchase_request_items.new_account_code');

        $model  =   $model->where('unit_purchase_requests.id','=', $id);

        return $model->get();
    }

    /**
     * [findTimelineById description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function findTimelineByUnit($request, $type, $with = [])
    {
        $model  =    $this->model;

        $model  =   $model->with($with);

        $model  =   $model->select([
            'unit_purchase_requests.id',
            'unit_purchase_requests.project_name',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.ref_number',
            'unit_purchase_requests.date_processed',
            'unit_purchase_requests.state',
            'unit_purchase_requests.total_amount',
            'unit_purchase_requests.mode_of_procurement',
            'unit_purchase_requests.date_processed',
            'unit_purchase_requests.date_processed as upr_created_at',
            'unit_purchase_requests.delay_count',
        ]);

        if(!\Sentinel::getUser()->hasRole("Admin")){

            $model  =   $model->where('unit_purchase_requests.units', '=', \Sentinel::getUser()->unit_id);
        }

        $model  =   $model->groupBy([
            'unit_purchase_requests.id',
            'unit_purchase_requests.project_name',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.ref_number',
            'unit_purchase_requests.date_processed',
            'unit_purchase_requests.state',
            'unit_purchase_requests.total_amount',
            'unit_purchase_requests.mode_of_procurement',
            'unit_purchase_requests.date_processed',
            'unit_purchase_requests.date_processed',
            'unit_purchase_requests.delay_count',
        ]);

        $model  =   $model->orderBy('unit_purchase_requests.mode_of_procurement');

        return $model->get();
    }

    /**
     * [findByPrograms description]
     *
     * @param  [type] $program [description]
     * @return [type]          [description]
     */
    public function findByPrograms($type = 'alternative', $status, $programs, $pcco = null, $unit = null, Request $request)
    {
        $dateTo = $request->get('date_to');
        $dateFrom = $request->get('date_from');

        $date    = \Carbon\Carbon::now();
        $yearto    = $date->format('Y');
        $yearfrom    = $date->format('Y');
        if($dateFrom != null)
        {
            $dateFrom  =   $dateFrom;
            $yearfrom  =   \Carbon\Carbon::createFromFormat('Y-m-d', $dateFrom)->format('Y');
        }

        if($dateTo != null)
        {
            $dateTo  =   $dateTo;
            $yearto  =   \Carbon\Carbon::createFromFormat('Y-m-d', $dateTo)->format('Y');
        }

        if($dateTo &&  $dateFrom == null)
        {
            $yearfrom  =   \Carbon\Carbon::createFromFormat('Y-m-d', $dateTo)->format('Y');
        }


        $model  =   $this->model;

        $model  =   $model->select([
            DB::raw("count(unit_purchase_requests.id) as upr_count"),
            DB::raw("IFNULL( SUM(CASE
             WHEN 5 * (DATEDIFF(NOW(), unit_purchase_requests.next_due) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.next_due) + WEEKDAY(NOW()) + 1, 1) > 0 and unit_purchase_requests.state != 'completed' THEN 1
             ELSE 0
           END),0) as delay_count"),
            DB::raw("count(unit_purchase_requests.completed_at) as completed_count"),
            DB::raw("
                count(unit_purchase_requests.id) -
                ( count(unit_purchase_requests.completed_at) )
                as ongoing_count"),
            DB::raw("sum(unit_purchase_requests.total_amount) as total_abc"),
            DB::raw("sum(purchase_orders.bid_amount) as total_bid"),
            DB::raw("(sum(unit_purchase_requests.total_amount) - sum(purchase_orders.bid_amount)) as total_residual"),
            DB::raw("5 * (DATEDIFF(vouchers.preaudit_date, unit_purchase_requests.date_processed) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.date_processed) + WEEKDAY(vouchers.preaudit_date) + 1, 1) as avg_days"),
            DB::raw(" avg( unit_purchase_requests.days - 43 ) as avg_delays"),
            'procurement_centers.name',
            'procurement_centers.programs',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.ref_number',
            'unit_purchase_requests.project_name',
            'unit_purchase_requests.cancel_reason',
            'unit_purchase_requests.id',
            'unit_purchase_requests.date_processed',
            'unit_purchase_requests.status',
            'unit_purchase_requests.last_remarks',
            'unit_purchase_requests.last_action',
            'unit_purchase_requests.next_due',
            'unit_purchase_requests.last_remarks',
            'unit_purchase_requests.next_step',
            'catered_units.short_code',
            DB::raw("5 * (DATEDIFF(NOW(), unit_purchase_requests.next_due) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.next_due) + WEEKDAY(NOW()) + 1, 1) as delay")
        ]);
        $model  =   $model->leftJoin('vouchers', 'vouchers.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('catered_units', 'catered_units.id', '=', 'unit_purchase_requests.units');
        $model  =   $model->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'unit_purchase_requests.procurement_office');

        // $model  =   $model->where('procurement_centers.name', '=', $name);
        // $model  =   $model->where('catered_units.short_code', '=', $programs);
        $model  =   $model->where('procurement_centers.programs', '=', $programs);

        $model  =   $model->where('unit_purchase_requests.status', '!=', 'draft');

        if($status == 'completed')
        {
            $model  =   $model->where('unit_purchase_requests.status', '<>', "cancelled");
            $model  =   $model->where('unit_purchase_requests.status', '=', "completed");
        }
        elseif($status == 'cancelled')
        {
            $model  =   $model->where('unit_purchase_requests.status', '=', "cancelled");
        }
        elseif($status == 'ongoing')
        {
            $model  =   $model->where('unit_purchase_requests.status', '<>', "completed");
            $model  =   $model->where('unit_purchase_requests.status', '<>', "cancelled");
        }
        else
        {

            $model  =   $model->havingRaw("(5 * (DATEDIFF(NOW(), unit_purchase_requests.next_due) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.next_due) + WEEKDAY(NOW()) + 1, 1) - (SELECT COUNT(*) FROM holidays WHERE holiday_date >= unit_purchase_requests.date_processed and holiday_date <= NOW() AND DAYOFWEEK(holiday_date) < 6 ) ) > 0");

            $model  =   $model->where('unit_purchase_requests.status', '<>', "completed");
            $model  =   $model->where('unit_purchase_requests.status', '<>', "cancelled");
            $model  =   $model->whereRaw("unit_purchase_requests.next_due <  NOW() ");
        }

        if($pcco != null)
        {
            $model  =   $model->where('procurement_centers.name', '=', $pcco);
        }

        if($unit != null)
        {
            $model  =   $model->where('catered_units.short_code', '=', $unit);
        }

        if($type != 'alternative')
        {
            $model  =   $model->where('mode_of_procurement', '=', 'public_bidding');
        }
        else
        {
            $model  =   $model->where('mode_of_procurement', '!=', 'public_bidding');
        }

        $model  = $model->whereRaw("YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' ");


        if(!\Sentinel::getUser()->hasRole('Admin') )
        {
            $model  =   $model->where('unit_purchase_requests.units','=', \Sentinel::getUser()->unit_id);
        }

        if($dateFrom != null){
          $model  =   $model->where('unit_purchase_requests.date_processed', '>=', $dateFrom);
        }
        if($dateTo != null){
          $model  =   $model->where('unit_purchase_requests.date_processed', '<=', $dateTo);
        }

        $model  = $model->whereRaw("YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' ");

        $model  =   $model->groupBy([
            'procurement_centers.name',
            'unit_purchase_requests.next_due',
            'procurement_centers.programs',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.cancel_reason',
            'unit_purchase_requests.next_due',
            'unit_purchase_requests.delay_count',
            'unit_purchase_requests.status',
            'catered_units.short_code',
            'unit_purchase_requests.ref_number',
            'unit_purchase_requests.last_remarks',
            'unit_purchase_requests.last_action',
            'unit_purchase_requests.project_name',
            'unit_purchase_requests.next_step',
            'unit_purchase_requests.id',
            'unit_purchase_requests.last_remarks',
            'unit_purchase_requests.date_processed',
            'vouchers.preaudit_date',
        ]);

        return $model->get();
    }
}
