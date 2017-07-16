<?php

namespace Revlv\Procurements\UnitPurchaseRequests;

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
            'unit_purchase_requests.date_prepared',
            'unit_purchase_requests.state',
            'unit_purchase_requests.total_amount',
            'unit_purchase_requests.date_processed',
            'unit_purchase_requests.date_prepared as upr_created_at',
            'unit_purchase_requests.delay_count',
            'request_for_quotations.id as rfq_id',
            'request_for_quotations.transaction_date as rfq_created_at',
            'request_for_quotations.completed_at as rfq_completed_at',
            'request_for_quotations.days as rfq_days',
            'request_for_quotations.close_days as rfq_closed_days',
            'request_for_quotations.close_remarks as rfq_close_remarks',
            'request_for_quotations.remarks as rfq_remarks',
            'invitation_for_quotation.id as ispq_id',
            'invitation_for_quotation.transaction_date as ispq_transaction_date',
            'ispq_quotations.delay_count as ispq_days',
            'ispq_quotations.remarks as ispq_remarks',
            'philgeps_posting.philgeps_posting as pp_completed_at',
            'philgeps_posting.days as pp_days',
            'philgeps_posting.id as pp_id',
            'philgeps_posting.remarks as pp_remarks',
            'canvassing.canvass_date as canvass_start_date',
            'canvassing.id as canvass_id',
            'canvassing.days as canvass_days',
            'canvassing.remarks as canvass_remarks',
            'notice_of_awards.id as noa_id',
            'notice_of_awards.days as noa_days',
            'notice_of_awards.approved_days as noa_approved_days',
            'notice_of_awards.received_days as noa_received_days',
            'notice_of_awards.remarks as noa_remarks',
            'notice_of_awards.approved_remarks as noa_approved_remarks',
            'notice_of_awards.received_remarks as noa_received_remarks',
            'notice_of_awards.awarded_date as noa_award_date',
            'notice_of_awards.accepted_date as noa_approved_date',
            'notice_of_awards.award_accepted_date as noa_award_accepted_date',
            'purchase_orders.id as po_id',
            'purchase_orders.days as po_days',
            'purchase_orders.remarks as po_remarks',
            'purchase_orders.purchase_date as po_create_date',
            'purchase_orders.mfo_received_date',
            'purchase_orders.funding_received_date',
            'purchase_orders.funding_days as po_fund_days',
            'purchase_orders.mfo_days as po_mfo_days',
            'purchase_orders.coa_days as po_coa_days',
            'purchase_orders.funding_remarks as po_funding_remarks',
            'purchase_orders.mfo_remarks as po_mfo_remarks',
            'purchase_orders.coa_remarks as po_coa_remarks',
            'purchase_orders.coa_approved_date',
            'notice_to_proceed.id as ntp_id',
            'notice_to_proceed.days as ntp_days',
            'notice_to_proceed.accepted_days as ntp_accepted_days',
            'notice_to_proceed.accepted_remarks as ntp_accepted_remarks',
            'notice_to_proceed.remarks as ntp_remarks',
            'notice_to_proceed.prepared_date as ntp_date',
            'notice_to_proceed.award_accepted_date as ntp_award_date',
            'delivery_orders.transaction_date as dr_date',
            'delivery_orders.id as dr_id',
            'delivery_orders.days as dr_days',
            'delivery_orders.remarks as dr_remarks',
            'delivery_orders.delivery_days as dr_delivery_days',
            'delivery_orders.delivery_remarks as dr_delivery_remarks',
            'delivery_orders.dr_coa_days as dr_dr_coa_days',
            'delivery_orders.dr_coa_remarks as dr_dr_coa_remarks',
            'delivery_orders.delivery_date',
            'delivery_orders.date_delivered_to_coa as dr_coa_date',
            'inspection_acceptance_report.id as tiac_id',
            'inspection_acceptance_report.accept_days as tiac_accept_days',
            'inspection_acceptance_report.accept_remarks as tiac_accept_remarks',
            'inspection_acceptance_report.days as tiac_days',
            'inspection_acceptance_report.remarks as tiac_remarks',
            'inspection_acceptance_report.inspection_date as dr_inspection',
            'inspection_acceptance_report.accepted_date as iar_accepted_date',
            'delivery_inspection.id as diir_id',
            'delivery_inspection.days as diir_days',
            'delivery_inspection.close_days as diir_close_days',
            'delivery_inspection.remarks as diir_remarks',
            'delivery_inspection.close_remarks as diir_close_remarks',
            'delivery_inspection.closed_date as di_close',
            'delivery_inspection.start_date as di_start',
            'vouchers.id as vou_id',
            'vouchers.days as vou_days',
            'vouchers.preaudit_days as vou_preaudit_days',
            'vouchers.preaudit_remarks as vou_preaudit_remarks',
            'vouchers.certify_days as vou_certify_days',
            'vouchers.certify_remarks as vou_certify_remarks',
            'vouchers.jev_days as vou_jev_days',
            'vouchers.jev_remarks as vou_jev_remarks',
            'vouchers.approved_days as vou_approved_days',
            'vouchers.approved_remarks as vou_approved_remarks',
            'vouchers.released_days as vou_released_days',
            'vouchers.released_remarks as vou_released_remarks',
            'vouchers.received_days as vou_received_days',
            'vouchers.received_remarks as vou_received_remarks',
            'vouchers.remarks as vou_remarks',
            'vouchers.created_at as vou_start',
            'vouchers.transaction_date as v_transaction_date',
            'vouchers.preaudit_date as preaudit_date',
            'vouchers.certify_date as certify_date',
            'vouchers.journal_entry_date as journal_entry_date',
            'vouchers.approval_date as vou_approval_date',
            'vouchers.payment_release_date as vou_release',
            'vouchers.payment_received_date as vou_received',
            // DB::raw("(select count(*) from holidays where holiday_date >= unit_purchase_requests.created_at and holiday_date <= NOW()) as holidays"),
            // DB::raw("datediff(NOW(), unit_purchase_requests.created_at ) as days"),
            // DB::raw("5 * (DATEDIFF(NOW(), unit_purchase_requests.created_at) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.created_at) + WEEKDAY(NOW()) + 1, 1) as working_days")
        ]);

        $model  =   $model->leftJoin('philgeps_posting', 'philgeps_posting.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('ispq_quotations', 'ispq_quotations.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('invitation_for_quotation', 'invitation_for_quotation.id', '=', 'ispq_quotations.ispq_id');
        $model  =   $model->leftJoin('canvassing', 'canvassing.upr_id', '=', 'unit_purchase_requests.id');
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

        $model  =   $model->whereYear('date_prepared', '=', $year);

        return $model->first();
    }

    /**
     *
     *
     * @return [type] [description]
     */
    public function getDelays()
    {
        $model  =   $this->model;


        $model  =   $model->select([
            'unit_purchase_requests.id',
            'unit_purchase_requests.project_name',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.ref_number',
            'unit_purchase_requests.date_prepared',
            'unit_purchase_requests.state',
            'unit_purchase_requests.total_amount',
            'unit_purchase_requests.date_processed',
            'unit_purchase_requests.date_prepared as upr_created_at',
            'request_for_quotations.transaction_date as rfq_created_at',
            'request_for_quotations.completed_at as rfq_completed_at',
            'invitation_for_quotation.transaction_date as ispq_transaction_date',
            'philgeps_posting.philgeps_posting as pp_completed_at',
            'canvassing.canvass_date as canvass_start_date',
            'notice_of_awards.awarded_date as noa_award_date',
            'notice_of_awards.accepted_date as noa_approved_date',
            'notice_of_awards.award_accepted_date as noa_award_accepted_date',
            'purchase_orders.purchase_date as po_create_date',
            'purchase_orders.mfo_received_date',
            'purchase_orders.funding_received_date',
            'purchase_orders.coa_approved_date',
            'notice_to_proceed.prepared_date as ntp_date',
            'notice_to_proceed.award_accepted_date as ntp_award_date',
            'delivery_orders.transaction_date as dr_date',
            'delivery_orders.delivery_date',
            'delivery_orders.date_delivered_to_coa as dr_coa_date',
            'inspection_acceptance_report.inspection_date as dr_inspection',
            'inspection_acceptance_report.accepted_date as iar_accepted_date',
            'delivery_inspection.closed_date as di_close',
            'delivery_inspection.start_date as di_start',
            'vouchers.created_at as vou_start',
            'vouchers.transaction_date as v_transaction_date',
            'vouchers.preaudit_date as preaudit_date',
            'vouchers.certify_date as certify_date',
            'vouchers.journal_entry_date as journal_entry_date',
            'vouchers.approval_date as vou_approval_date',
            'vouchers.payment_release_date as vou_release',
            'vouchers.payment_received_date as vou_received',
            // DB::raw("(select count(*) from holidays where holiday_date >= unit_purchase_requests.created_at and holiday_date <= NOW()) as holidays"),
            // DB::raw("datediff(NOW(), unit_purchase_requests.created_at ) as days"),
            // DB::raw("5 * (DATEDIFF(NOW(), unit_purchase_requests.created_at) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.created_at) + WEEKDAY(NOW()) + 1, 1) as working_days")
        ]);

        $model  =   $model->where('state', '!=', 'completed');
        $model  =   $model->where('state', '!=', 'Terminated pass');
        $model  =   $model->where('state', '!=', 'Terminated failed');

        $model  =   $model->leftJoin('philgeps_posting', 'philgeps_posting.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('ispq_quotations', 'ispq_quotations.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('invitation_for_quotation', 'invitation_for_quotation.id', '=', 'ispq_quotations.ispq_id');
        $model  =   $model->leftJoin('canvassing', 'canvassing.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('notice_of_awards', 'notice_of_awards.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('notice_to_proceed', 'notice_to_proceed.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('delivery_orders', 'delivery_orders.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('inspection_acceptance_report', 'inspection_acceptance_report.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('delivery_inspection', 'delivery_inspection.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('vouchers', 'vouchers.upr_id', '=', 'unit_purchase_requests.id');

        $model  =   $model->get();

        return $model;
    }
}
