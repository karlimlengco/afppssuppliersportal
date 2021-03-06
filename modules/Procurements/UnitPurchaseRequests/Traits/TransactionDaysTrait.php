<?php

namespace Revlv\Procurements\UnitPurchaseRequests\Traits;

use Illuminate\Http\Request;
use DB;
use Datatables;

trait TransactionDaysTrait
{

    /**
     * [getDatatable description]
     *
     * @param  [int]    $company_id ['company id ']
     * @return [type]               [description]
     */
    public function getTransactionDay($request, $search = null)
    {
        $model  =    $this->model;

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
            $date_to  =   $dateTo;
            $yearto  =   \Carbon\Carbon::createFromFormat('Y-m-d', $date_to)->format('Y');
        }

        if($dateTo &&  $dateFrom == null)
        {
            $dateFrom  =   $date_to;
            $yearfrom  =   \Carbon\Carbon::createFromFormat('Y-m-d', $dateFrom)->format('Y');
        }

        if($request->has('date_to') != null && $request->has('date_from') != null && $search != null)
        {
          $dateTo = $request->get('date_to');
          $dateFrom = $request->get('date_from');
          $model  =   $model->select([
              'unit_purchase_requests.id',
              'procurement_centers.name',
              'catered_units.short_code',
              'unit_purchase_requests.project_name',
              'unit_purchase_requests.units',
              'unit_purchase_requests.chargeability',
              'unit_purchase_requests.mode_of_procurement',
              'unit_purchase_requests.upr_number',
              'unit_purchase_requests.ref_number',
              'unit_purchase_requests.date_processed',
              'unit_purchase_requests.state',
              'unit_purchase_requests.total_amount',
              'unit_purchase_requests.date_processed',
              DB::raw("5 * (DATEDIFF(vouchers.preaudit_date, unit_purchase_requests.date_processed) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.date_processed) + WEEKDAY(vouchers.preaudit_date) + 1, 1) as calendar_days "),

              'unit_purchase_requests.date_processed as upr_created_at',
              'unit_purchase_requests.date_processed as upr_created_at',

              'document_acceptance.days as doc_days',
              'document_acceptance.approved_date as doc_date',
              'invitation_to_bid.days as itb_days',
              'invitation_to_bid.transaction_date as itb_date',
              'pre_bid_conferences.days as prebid_days',
              'pre_bid_conferences.transaction_date as prebid_date',
              'bid_opening.days as bid_days',
              'bid_opening.transaction_date as bid_date',
              'pre_proc.days as proc_days',
              'pre_proc.pre_proc_date as proc_date',
              'post_qualification.days as pq_days',
              'post_qualification.transaction_date as pq_date',
              'request_for_quotations.days as rfq_days',
              'request_for_quotations.close_days as rfq_close_days',
              'request_for_quotations.transaction_date as rfq_created_at',
              'request_for_quotations.completed_at as rfq_completed_at',
              'invitation_for_quotation.transaction_date as ispq_transaction_date',

              DB::raw(" (select philgeps_posting.days from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where philgeps_posting.upr_id = unit_purchase_requests.id  AND unit_purchase_requests.date_processed <= $dateTo AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND  unit_purchase_requests.date_processed >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% order by philgeps_posting.created_at desc limit 1) as pp_days "),

              DB::raw(" (select philgeps_posting.philgeps_posting from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id  where philgeps_posting.upr_id = unit_purchase_requests.id  AND unit_purchase_requests.date_processed <= $dateTo AND unit_purchase_requests.date_processed >= $dateFrom AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND  unit_purchase_requests.upr_number LIKE %$search% order by philgeps_posting.created_at desc limit 1) as pp_completed_at "),

              'canvassing.canvass_date as canvass_start_date',
              'canvassing.days as canvass_days',
              'notice_of_awards.days as noa_days',
              'notice_of_awards.approved_days as noa_approved_days',
              'notice_of_awards.received_days as noa_received_days',
              'notice_of_awards.awarded_date as noa_award_date',
              'notice_of_awards.accepted_date as noa_approved_date',
              'notice_of_awards.award_accepted_date as noa_award_accepted_date',
              'purchase_orders.days as po_days',
              'purchase_orders.funding_days as po_funding_days',
              'purchase_orders.mfo_days as po_mfo_days',
              'purchase_orders.coa_days as po_coa_days',
              'purchase_orders.bid_amount',
              'purchase_orders.purchase_date as po_create_date',
              'purchase_orders.mfo_received_date',
              'purchase_orders.funding_received_date',
              'purchase_orders.coa_approved_date',
              'purchase_orders.delivery_terms',
              'notice_to_proceed.days as ntp_days',
              'notice_to_proceed.accepted_days as ntp_accepted_days',
              'notice_to_proceed.prepared_date as ntp_date',
              'notice_to_proceed.award_accepted_date as ntp_award_date',


              DB::raw(" (select delivery_orders.days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND unit_purchase_requests.date_processed <= $dateTo AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND  unit_purchase_requests.date_processed >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_days "),

              DB::raw(" (select delivery_orders.delivery_days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND unit_purchase_requests.date_processed <= $dateTo AND unit_purchase_requests.date_processed >= $dateFrom AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND  unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_delivery_days "),

              DB::raw(" (select delivery_orders.dr_coa_days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND unit_purchase_requests.date_processed <= $dateTo AND unit_purchase_requests.date_processed >= $dateFrom AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND  unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_dr_coa_days "),

              DB::raw(" (select delivery_orders.transaction_date from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND unit_purchase_requests.date_processed <= $dateTo AND unit_purchase_requests.date_processed >= $dateFrom AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND  unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_date "),

              DB::raw(" (select delivery_orders.delivery_date from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND unit_purchase_requests.date_processed <= $dateTo AND unit_purchase_requests.date_processed >= $dateFrom AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND  unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_date "),

              DB::raw(" (select delivery_orders.date_delivered_to_coa from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed <= $dateTo AND unit_purchase_requests.date_processed >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_coa_date "),
              'inspection_acceptance_report.days as dr_inspection_days',
              'inspection_acceptance_report.accept_days as dr_inspection_accept_days',
              'inspection_acceptance_report.inspection_date as dr_inspection',
              'inspection_acceptance_report.accepted_date as iar_accepted_date',
              'delivery_inspection.days as di_days',
              'delivery_inspection.close_days as di_close_days',
              'delivery_inspection.closed_date as di_close',
              'delivery_inspection.start_date as di_start',
              'vouchers.days as vou_days',
              'vouchers.preaudit_days as vou_preaudit_days',
              'vouchers.jev_days as vou_jev_days',
              'vouchers.certify_days as vou_certify_days',
              'vouchers.check_days as vou_check_days',
              'vouchers.approved_days as vou_approved_days',
              'vouchers.released_days as vou_released_days',
              'vouchers.created_at as vou_start',
              'vouchers.transaction_date as v_transaction_date',
              'vouchers.preaudit_date as preaudit_date',
              'vouchers.certify_date as certify_date',
              'vouchers.journal_entry_date as journal_entry_date',
              'vouchers.approval_date as vou_approval_date',
              'vouchers.payment_release_date as vou_release',
          ]);
        }
        elseif($request->has('date_to') != null && $request->has('date_from') != null)
        {
          $dateTo = $request->get('date_to');
          $dateFrom = $request->get('date_from');
          $model  =   $model->select([
              'unit_purchase_requests.id',
              'procurement_centers.name',
              'catered_units.short_code',
              'unit_purchase_requests.project_name',
              'unit_purchase_requests.mode_of_procurement',
              'unit_purchase_requests.upr_number',
              'unit_purchase_requests.units',
              'unit_purchase_requests.chargeability',
              'unit_purchase_requests.ref_number',
              'unit_purchase_requests.date_processed',
              'unit_purchase_requests.state',
              'unit_purchase_requests.total_amount',
              'unit_purchase_requests.date_processed',
              DB::raw("5 * (DATEDIFF(vouchers.preaudit_date, unit_purchase_requests.date_processed) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.date_processed) + WEEKDAY(vouchers.preaudit_date) + 1, 1) as calendar_days "),

              'unit_purchase_requests.date_processed as upr_created_at',
              'unit_purchase_requests.date_processed as upr_created_at',

              'document_acceptance.days as doc_days',
              'document_acceptance.approved_date as doc_date',
              'invitation_to_bid.days as itb_days',
              'invitation_to_bid.transaction_date as itb_date',
              'pre_bid_conferences.days as prebid_days',
              'pre_bid_conferences.transaction_date as prebid_date',
              'bid_opening.days as bid_days',
              'bid_opening.transaction_date as bid_date',
              'pre_proc.days as proc_days',
              'pre_proc.pre_proc_date as proc_date',
              'post_qualification.days as pq_days',
              'post_qualification.transaction_date as pq_date',
              'request_for_quotations.days as rfq_days',
              'request_for_quotations.close_days as rfq_close_days',
              'request_for_quotations.transaction_date as rfq_created_at',
              'request_for_quotations.completed_at as rfq_completed_at',
              'invitation_for_quotation.transaction_date as ispq_transaction_date',

              DB::raw(" (select philgeps_posting.days from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where philgeps_posting.upr_id = unit_purchase_requests.id  AND unit_purchase_requests.date_processed <= $dateTo AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed >= $dateFrom order by philgeps_posting.created_at desc limit 1) as pp_days "),

              DB::raw(" (select philgeps_posting.philgeps_posting from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id  where philgeps_posting.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed <= $dateTo AND unit_purchase_requests.date_processed >= $dateFrom order by philgeps_posting.created_at desc limit 1) as pp_completed_at "),

              'canvassing.canvass_date as canvass_start_date',
              'canvassing.days as canvass_days',
              'notice_of_awards.days as noa_days',
              'notice_of_awards.approved_days as noa_approved_days',
              'notice_of_awards.received_days as noa_received_days',
              'notice_of_awards.awarded_date as noa_award_date',
              'notice_of_awards.accepted_date as noa_approved_date',
              'notice_of_awards.award_accepted_date as noa_award_accepted_date',
              'purchase_orders.days as po_days',
              'purchase_orders.funding_days as po_funding_days',
              'purchase_orders.mfo_days as po_mfo_days',
              'purchase_orders.coa_days as po_coa_days',
              'purchase_orders.bid_amount',
              'purchase_orders.purchase_date as po_create_date',
              'purchase_orders.mfo_received_date',
              'purchase_orders.funding_received_date',
              'purchase_orders.coa_approved_date',
              'purchase_orders.delivery_terms',
              'notice_to_proceed.days as ntp_days',
              'notice_to_proceed.accepted_days as ntp_accepted_days',
              'notice_to_proceed.prepared_date as ntp_date',
              'notice_to_proceed.award_accepted_date as ntp_award_date',


              DB::raw(" (select delivery_orders.days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND unit_purchase_requests.date_processed <= $dateTo AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed >= $dateFrom order by delivery_orders.created_at desc limit 1) as dr_days "),

              DB::raw(" (select delivery_orders.delivery_days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed <= $dateTo AND unit_purchase_requests.date_processed >= $dateFrom order by delivery_orders.created_at desc limit 1) as dr_delivery_days "),

              DB::raw(" (select delivery_orders.dr_coa_days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed <= $dateTo AND unit_purchase_requests.date_processed >= $dateFrom order by delivery_orders.created_at desc limit 1) as dr_dr_coa_days "),

              DB::raw(" (select delivery_orders.transaction_date from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed <= $dateTo AND unit_purchase_requests.date_processed >= $dateFrom order by delivery_orders.created_at desc limit 1) as dr_date "),

              DB::raw(" (select delivery_orders.delivery_date from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed <= $dateTo AND unit_purchase_requests.date_processed >= $dateFrom order by delivery_orders.created_at desc limit 1) as dr_date "),

              DB::raw(" (select delivery_orders.date_delivered_to_coa from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed <= $dateTo AND unit_purchase_requests.date_processed >= $dateFrom order by delivery_orders.created_at desc limit 1) as dr_coa_date "),
              'inspection_acceptance_report.days as dr_inspection_days',
              'inspection_acceptance_report.accept_days as dr_inspection_accept_days',
              'inspection_acceptance_report.inspection_date as dr_inspection',
              'inspection_acceptance_report.accepted_date as iar_accepted_date',
              'delivery_inspection.days as di_days',
              'delivery_inspection.close_days as di_close_days',
              'delivery_inspection.closed_date as di_close',
              'delivery_inspection.start_date as di_start',
              'vouchers.days as vou_days',
              'vouchers.preaudit_days as vou_preaudit_days',
              'vouchers.jev_days as vou_jev_days',
              'vouchers.certify_days as vou_certify_days',
              'vouchers.check_days as vou_check_days',
              'vouchers.approved_days as vou_approved_days',
              'vouchers.released_days as vou_released_days',
              'vouchers.created_at as vou_start',
              'vouchers.transaction_date as v_transaction_date',
              'vouchers.preaudit_date as preaudit_date',
              'vouchers.certify_date as certify_date',
              'vouchers.journal_entry_date as journal_entry_date',
              'vouchers.approval_date as vou_approval_date',
              'vouchers.payment_release_date as vou_release',
          ]);
        }
        elseif($request->has('date_from') != null && $search != null)
        {
            $dateFrom = $request->get('date_from');
            $model  =   $model->select([
                'unit_purchase_requests.id',
              'procurement_centers.name',
                'catered_units.short_code',
                'unit_purchase_requests.units',
                'unit_purchase_requests.chargeability',
                'unit_purchase_requests.project_name',
                'unit_purchase_requests.mode_of_procurement',
                'unit_purchase_requests.upr_number',
                'unit_purchase_requests.ref_number',
                'unit_purchase_requests.date_processed',
                'unit_purchase_requests.state',
                'unit_purchase_requests.total_amount',
                'unit_purchase_requests.date_processed',
                DB::raw("5 * (DATEDIFF(vouchers.preaudit_date, unit_purchase_requests.date_processed) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.date_processed) + WEEKDAY(vouchers.preaudit_date) + 1, 1) as calendar_days "),

                'unit_purchase_requests.date_processed as upr_created_at',
                'unit_purchase_requests.date_processed as upr_created_at',

                'document_acceptance.days as doc_days',
                'document_acceptance.approved_date as doc_date',
                'invitation_to_bid.days as itb_days',
                'invitation_to_bid.transaction_date as itb_date',
                'pre_bid_conferences.days as prebid_days',
                'pre_bid_conferences.transaction_date as prebid_date',
                'bid_opening.days as bid_days',
                'bid_opening.transaction_date as bid_date',
                'pre_proc.days as proc_days',
                'pre_proc.pre_proc_date as proc_date',
                'post_qualification.days as pq_days',
                'post_qualification.transaction_date as pq_date',
                'request_for_quotations.days as rfq_days',
                'request_for_quotations.close_days as rfq_close_days',
                'request_for_quotations.transaction_date as rfq_created_at',
                'request_for_quotations.completed_at as rfq_completed_at',
                'invitation_for_quotation.transaction_date as ispq_transaction_date',

                DB::raw(" (select philgeps_posting.days from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where philgeps_posting.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% order by philgeps_posting.created_at desc limit 1) as pp_days "),

                DB::raw(" (select philgeps_posting.philgeps_posting from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id  where philgeps_posting.upr_id = unit_purchase_requests.id AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% order by philgeps_posting.created_at desc limit 1) as pp_completed_at "),

                'canvassing.canvass_date as canvass_start_date',
                'canvassing.days as canvass_days',
                'notice_of_awards.days as noa_days',
                'notice_of_awards.approved_days as noa_approved_days',
                'notice_of_awards.received_days as noa_received_days',
                'notice_of_awards.awarded_date as noa_award_date',
                'notice_of_awards.accepted_date as noa_approved_date',
                'notice_of_awards.award_accepted_date as noa_award_accepted_date',
                'purchase_orders.days as po_days',
                'purchase_orders.bid_amount',
                'purchase_orders.funding_days as po_funding_days',
                'purchase_orders.mfo_days as po_mfo_days',
                'purchase_orders.coa_days as po_coa_days',
                'purchase_orders.purchase_date as po_create_date',
                'purchase_orders.mfo_received_date',
                'purchase_orders.funding_received_date',
                'purchase_orders.coa_approved_date',
                'purchase_orders.delivery_terms',
                'notice_to_proceed.days as ntp_days',
                'notice_to_proceed.accepted_days as ntp_accepted_days',
                'notice_to_proceed.prepared_date as ntp_date',
                'notice_to_proceed.award_accepted_date as ntp_award_date',


                DB::raw(" (select delivery_orders.days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id AND unit_purchase_requests.date_processed >= $dateFrom AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_days "),

                DB::raw(" (select delivery_orders.delivery_days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_delivery_days "),

                DB::raw(" (select delivery_orders.dr_coa_days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_dr_coa_days "),

                DB::raw(" (select delivery_orders.transaction_date from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_date "),

                DB::raw(" (select delivery_orders.delivery_date from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_date "),

                DB::raw(" (select delivery_orders.date_delivered_to_coa from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_coa_date "),
                'inspection_acceptance_report.days as dr_inspection_days',
                'inspection_acceptance_report.accept_days as dr_inspection_accept_days',
                'inspection_acceptance_report.inspection_date as dr_inspection',
                'inspection_acceptance_report.accepted_date as iar_accepted_date',
                'delivery_inspection.days as di_days',
                'delivery_inspection.close_days as di_close_days',
                'delivery_inspection.closed_date as di_close',
                'delivery_inspection.start_date as di_start',
                'vouchers.days as vou_days',
                'vouchers.preaudit_days as vou_preaudit_days',
                'vouchers.jev_days as vou_jev_days',
                'vouchers.certify_days as vou_certify_days',
                'vouchers.check_days as vou_check_days',
                'vouchers.approved_days as vou_approved_days',
                'vouchers.released_days as vou_released_days',
                'vouchers.created_at as vou_start',
                'vouchers.transaction_date as v_transaction_date',
                'vouchers.preaudit_date as preaudit_date',
                'vouchers.certify_date as certify_date',
                'vouchers.journal_entry_date as journal_entry_date',
                'vouchers.approval_date as vou_approval_date',
                'vouchers.payment_release_date as vou_release',
            ]);
        }
        elseif($request->has('date_from') != null)
        {
            $dateFrom = $request->get('date_from');
            $model  =   $model->select([
                'unit_purchase_requests.id',
              'procurement_centers.name',
                'catered_units.short_code',
                'unit_purchase_requests.units',
                'unit_purchase_requests.chargeability',
                'unit_purchase_requests.project_name',
                'unit_purchase_requests.mode_of_procurement',
                'unit_purchase_requests.upr_number',
                'unit_purchase_requests.ref_number',
                'unit_purchase_requests.date_processed',
                'unit_purchase_requests.state',
                'unit_purchase_requests.total_amount',
                'unit_purchase_requests.date_processed',
                DB::raw("5 * (DATEDIFF(vouchers.preaudit_date, unit_purchase_requests.date_processed) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.date_processed) + WEEKDAY(vouchers.preaudit_date) + 1, 1) as calendar_days "),

                'unit_purchase_requests.date_processed as upr_created_at',
                'unit_purchase_requests.date_processed as upr_created_at',

                'document_acceptance.days as doc_days',
                'document_acceptance.approved_date as doc_date',
                'invitation_to_bid.days as itb_days',
                'invitation_to_bid.transaction_date as itb_date',
                'pre_bid_conferences.days as prebid_days',
                'pre_bid_conferences.transaction_date as prebid_date',
                'bid_opening.days as bid_days',
                'bid_opening.transaction_date as bid_date',
                'pre_proc.days as proc_days',
                'pre_proc.pre_proc_date as proc_date',
                'post_qualification.days as pq_days',
                'post_qualification.transaction_date as pq_date',
                'request_for_quotations.days as rfq_days',
                'request_for_quotations.close_days as rfq_close_days',
                'request_for_quotations.transaction_date as rfq_created_at',
                'request_for_quotations.completed_at as rfq_completed_at',
                'invitation_for_quotation.transaction_date as ispq_transaction_date',

                DB::raw(" (select philgeps_posting.days from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where philgeps_posting.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed >= $dateFrom order by philgeps_posting.created_at desc limit 1) as pp_days "),

                DB::raw(" (select philgeps_posting.philgeps_posting from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id  where philgeps_posting.upr_id = unit_purchase_requests.id AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed >= $dateFrom order by philgeps_posting.created_at desc limit 1) as pp_completed_at "),

                'canvassing.canvass_date as canvass_start_date',
                'canvassing.days as canvass_days',
                'notice_of_awards.days as noa_days',
                'notice_of_awards.approved_days as noa_approved_days',
                'notice_of_awards.received_days as noa_received_days',
                'notice_of_awards.awarded_date as noa_award_date',
                'notice_of_awards.accepted_date as noa_approved_date',
                'notice_of_awards.award_accepted_date as noa_award_accepted_date',
                'purchase_orders.days as po_days',
                'purchase_orders.funding_days as po_funding_days',
                'purchase_orders.mfo_days as po_mfo_days',
                'purchase_orders.coa_days as po_coa_days',
                'purchase_orders.bid_amount',
                'purchase_orders.purchase_date as po_create_date',
                'purchase_orders.mfo_received_date',
                'purchase_orders.funding_received_date',
                'purchase_orders.coa_approved_date',
                'purchase_orders.delivery_terms',
                'notice_to_proceed.days as ntp_days',
                'notice_to_proceed.accepted_days as ntp_accepted_days',
                'notice_to_proceed.prepared_date as ntp_date',
                'notice_to_proceed.award_accepted_date as ntp_award_date',


                DB::raw(" (select delivery_orders.days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed >= $dateFrom order by delivery_orders.created_at desc limit 1) as dr_days "),

                DB::raw(" (select delivery_orders.delivery_days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed >= $dateFrom order by delivery_orders.created_at desc limit 1) as dr_delivery_days "),

                DB::raw(" (select delivery_orders.dr_coa_days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed >= $dateFrom order by delivery_orders.created_at desc limit 1) as dr_dr_coa_days "),

                DB::raw(" (select delivery_orders.transaction_date from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed >= $dateFrom order by delivery_orders.created_at desc limit 1) as dr_date "),

                DB::raw(" (select delivery_orders.delivery_date from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed >= $dateFrom order by delivery_orders.created_at desc limit 1) as dr_date "),

                DB::raw(" (select delivery_orders.date_delivered_to_coa from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed >= $dateFrom order by delivery_orders.created_at desc limit 1) as dr_coa_date "),
                'inspection_acceptance_report.days as dr_inspection_days',
                'inspection_acceptance_report.accept_days as dr_inspection_accept_days',
                'inspection_acceptance_report.inspection_date as dr_inspection',
                'inspection_acceptance_report.accepted_date as iar_accepted_date',
                'delivery_inspection.days as di_days',
                'delivery_inspection.close_days as di_close_days',
                'delivery_inspection.closed_date as di_close',
                'delivery_inspection.start_date as di_start',
                'vouchers.days as vou_days',
                'vouchers.preaudit_days as vou_preaudit_days',
                'vouchers.jev_days as vou_jev_days',
                'vouchers.certify_days as vou_certify_days',
                'vouchers.check_days as vou_check_days',
                'vouchers.approved_days as vou_approved_days',
                'vouchers.released_days as vou_released_days',
                'vouchers.created_at as vou_start',
                'vouchers.transaction_date as v_transaction_date',
                'vouchers.preaudit_date as preaudit_date',
                'vouchers.certify_date as certify_date',
                'vouchers.journal_entry_date as journal_entry_date',
                'vouchers.approval_date as vou_approval_date',
                'vouchers.payment_release_date as vou_release',
            ]);
        }
        elseif($request->has('date_to') != null && $search != null)
        {
            $dateTo = $request->get('date_to');
            $model  =   $model->select([
                'unit_purchase_requests.id',
              'procurement_centers.name',
                'catered_units.short_code',
                'unit_purchase_requests.units',
                'unit_purchase_requests.chargeability',
                'unit_purchase_requests.project_name',
                'unit_purchase_requests.mode_of_procurement',
                'unit_purchase_requests.upr_number',
                'unit_purchase_requests.ref_number',
                'unit_purchase_requests.date_processed',
                'unit_purchase_requests.state',
                'unit_purchase_requests.total_amount',
                'unit_purchase_requests.date_processed',
                DB::raw("5 * (DATEDIFF(vouchers.preaudit_date, unit_purchase_requests.date_processed) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.date_processed) + WEEKDAY(vouchers.preaudit_date) + 1, 1) as calendar_days "),

                'unit_purchase_requests.date_processed as upr_created_at',
                'unit_purchase_requests.date_processed as upr_created_at',

                'document_acceptance.days as doc_days',
                'document_acceptance.approved_date as doc_date',
                'invitation_to_bid.days as itb_days',
                'invitation_to_bid.transaction_date as itb_date',
                'pre_bid_conferences.days as prebid_days',
                'pre_bid_conferences.transaction_date as prebid_date',
                'bid_opening.days as bid_days',
                'bid_opening.transaction_date as bid_date',
                'pre_proc.days as proc_days',
                'pre_proc.pre_proc_date as proc_date',
                'post_qualification.days as pq_days',
                'post_qualification.transaction_date as pq_date',
                'request_for_quotations.days as rfq_days',
                'request_for_quotations.close_days as rfq_close_days',
                'request_for_quotations.transaction_date as rfq_created_at',
                'request_for_quotations.completed_at as rfq_completed_at',
                'invitation_for_quotation.transaction_date as ispq_transaction_date',

                DB::raw(" (select philgeps_posting.days from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where philgeps_posting.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search% order by philgeps_posting.created_at desc limit 1) as pp_days "),

                DB::raw(" (select philgeps_posting.philgeps_posting from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id  where philgeps_posting.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search% order by philgeps_posting.created_at desc limit 1) as pp_completed_at "),

                'canvassing.canvass_date as canvass_start_date',
                'canvassing.days as canvass_days',
                'notice_of_awards.days as noa_days',
                'notice_of_awards.approved_days as noa_approved_days',
                'notice_of_awards.received_days as noa_received_days',
                'notice_of_awards.awarded_date as noa_award_date',
                'notice_of_awards.accepted_date as noa_approved_date',
                'notice_of_awards.award_accepted_date as noa_award_accepted_date',
                'purchase_orders.days as po_days',
                'purchase_orders.funding_days as po_funding_days',
                'purchase_orders.mfo_days as po_mfo_days',
                'purchase_orders.coa_days as po_coa_days',
                'purchase_orders.purchase_date as po_create_date',
                'purchase_orders.mfo_received_date',
                'purchase_orders.bid_amount',
                'purchase_orders.funding_received_date',
                'purchase_orders.coa_approved_date',
                'purchase_orders.delivery_terms',
                'notice_to_proceed.days as ntp_days',
                'notice_to_proceed.accepted_days as ntp_accepted_days',
                'notice_to_proceed.prepared_date as ntp_date',
                'notice_to_proceed.award_accepted_date as ntp_award_date',


                DB::raw(" (select delivery_orders.days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND unit_purchase_requests.date_processed <= $dateTo AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_days "),

                DB::raw(" (select delivery_orders.delivery_days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_delivery_days "),

                DB::raw(" (select delivery_orders.dr_coa_days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_dr_coa_days "),

                DB::raw(" (select delivery_orders.transaction_date from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_date "),

                DB::raw(" (select delivery_orders.delivery_date from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_date "),

                DB::raw(" (select delivery_orders.date_delivered_to_coa from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_coa_date "),
                'inspection_acceptance_report.days as dr_inspection_days',
                'inspection_acceptance_report.accept_days as dr_inspection_accept_days',
                'inspection_acceptance_report.inspection_date as dr_inspection',
                'inspection_acceptance_report.accepted_date as iar_accepted_date',
                'delivery_inspection.days as di_days',
                'delivery_inspection.close_days as di_close_days',
                'delivery_inspection.closed_date as di_close',
                'delivery_inspection.start_date as di_start',
                'vouchers.days as vou_days',
                'vouchers.preaudit_days as vou_preaudit_days',
                'vouchers.jev_days as vou_jev_days',
                'vouchers.certify_days as vou_certify_days',
                'vouchers.check_days as vou_check_days',
                'vouchers.approved_days as vou_approved_days',
                'vouchers.released_days as vou_released_days',
                'vouchers.created_at as vou_start',
                'vouchers.transaction_date as v_transaction_date',
                'vouchers.preaudit_date as preaudit_date',
                'vouchers.certify_date as certify_date',
                'vouchers.journal_entry_date as journal_entry_date',
                'vouchers.approval_date as vou_approval_date',
                'vouchers.payment_release_date as vou_release',
            ]);
        }
        elseif($search != null)
        {
            $dateTo = $request->get('date_to');
            $model  =   $model->select([
                'unit_purchase_requests.id',
              'procurement_centers.name',
                'catered_units.short_code',
                'unit_purchase_requests.units',
                'unit_purchase_requests.chargeability',
                'unit_purchase_requests.project_name',
                'unit_purchase_requests.mode_of_procurement',
                'unit_purchase_requests.upr_number',
                'unit_purchase_requests.ref_number',
                'unit_purchase_requests.date_processed',
                'unit_purchase_requests.state',
                'unit_purchase_requests.total_amount',
                'unit_purchase_requests.date_processed',
                DB::raw("5 * (DATEDIFF(vouchers.preaudit_date, unit_purchase_requests.date_processed) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.date_processed) + WEEKDAY(vouchers.preaudit_date) + 1, 1) as calendar_days "),

                'unit_purchase_requests.date_processed as upr_created_at',
                'unit_purchase_requests.date_processed as upr_created_at',

                'document_acceptance.days as doc_days',
                'document_acceptance.approved_date as doc_date',
                'invitation_to_bid.days as itb_days',
                'invitation_to_bid.transaction_date as itb_date',
                'pre_bid_conferences.days as prebid_days',
                'pre_bid_conferences.transaction_date as prebid_date',
                'bid_opening.days as bid_days',
                'bid_opening.transaction_date as bid_date',
                'pre_proc.days as proc_days',
                'pre_proc.pre_proc_date as proc_date',
                'post_qualification.days as pq_days',
                'post_qualification.transaction_date as pq_date',
                'request_for_quotations.days as rfq_days',
                'request_for_quotations.close_days as rfq_close_days',
                'request_for_quotations.transaction_date as rfq_created_at',
                'request_for_quotations.completed_at as rfq_completed_at',
                'invitation_for_quotation.transaction_date as ispq_transaction_date',

                DB::raw(" (select philgeps_posting.days from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where philgeps_posting.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.upr_number LIKE %$search% order by philgeps_posting.created_at desc limit 1) as pp_days "),

                DB::raw(" (select philgeps_posting.philgeps_posting from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id  where philgeps_posting.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.upr_number LIKE %$search% order by philgeps_posting.created_at desc limit 1) as pp_completed_at "),

                'canvassing.canvass_date as canvass_start_date',
                'canvassing.days as canvass_days',
                'notice_of_awards.days as noa_days',
                'notice_of_awards.approved_days as noa_approved_days',
                'notice_of_awards.received_days as noa_received_days',
                'notice_of_awards.awarded_date as noa_award_date',
                'notice_of_awards.accepted_date as noa_approved_date',
                'notice_of_awards.award_accepted_date as noa_award_accepted_date',
                'purchase_orders.days as po_days',
                'purchase_orders.funding_days as po_funding_days',
                'purchase_orders.mfo_days as po_mfo_days',
                'purchase_orders.bid_amount',
                'purchase_orders.coa_days as po_coa_days',
                'purchase_orders.purchase_date as po_create_date',
                'purchase_orders.mfo_received_date',
                'purchase_orders.funding_received_date',
                'purchase_orders.coa_approved_date',
                'purchase_orders.delivery_terms',
                'notice_to_proceed.days as ntp_days',
                'notice_to_proceed.accepted_days as ntp_accepted_days',
                'notice_to_proceed.prepared_date as ntp_date',
                'notice_to_proceed.award_accepted_date as ntp_award_date',


                DB::raw(" (select delivery_orders.days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_days "),

                DB::raw(" (select delivery_orders.delivery_days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_delivery_days "),

                DB::raw(" (select delivery_orders.dr_coa_days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_dr_coa_days "),

                DB::raw(" (select delivery_orders.transaction_date from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_date "),

                DB::raw(" (select delivery_orders.delivery_date from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_date "),

                DB::raw(" (select delivery_orders.date_delivered_to_coa from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.upr_number LIKE %$search% order by delivery_orders.created_at desc limit 1) as dr_coa_date "),
                'inspection_acceptance_report.days as dr_inspection_days',
                'inspection_acceptance_report.accept_days as dr_inspection_accept_days',
                'inspection_acceptance_report.inspection_date as dr_inspection',
                'inspection_acceptance_report.accepted_date as iar_accepted_date',
                'delivery_inspection.days as di_days',
                'delivery_inspection.close_days as di_close_days',
                'delivery_inspection.closed_date as di_close',
                'delivery_inspection.start_date as di_start',
                'vouchers.days as vou_days',
                'vouchers.preaudit_days as vou_preaudit_days',
                'vouchers.jev_days as vou_jev_days',
                'vouchers.certify_days as vou_certify_days',
                'vouchers.check_days as vou_check_days',
                'vouchers.approved_days as vou_approved_days',
                'vouchers.released_days as vou_released_days',
                'vouchers.created_at as vou_start',
                'vouchers.transaction_date as v_transaction_date',
                'vouchers.preaudit_date as preaudit_date',
                'vouchers.certify_date as certify_date',
                'vouchers.journal_entry_date as journal_entry_date',
                'vouchers.approval_date as vou_approval_date',
                'vouchers.payment_release_date as vou_release',
            ]);
        }
        elseif($request->has('date_to') != null)
        {
            $dateTo = $request->get('date_to');
            $model  =   $model->select([
                'unit_purchase_requests.id',
              'procurement_centers.name',
                'catered_units.short_code',
                'unit_purchase_requests.units',
                'unit_purchase_requests.chargeability',
                'unit_purchase_requests.project_name',
                'unit_purchase_requests.mode_of_procurement',
                'unit_purchase_requests.upr_number',
                'unit_purchase_requests.ref_number',
                'unit_purchase_requests.date_processed',
                'unit_purchase_requests.state',
                'unit_purchase_requests.total_amount',
                'unit_purchase_requests.date_processed',
                DB::raw("5 * (DATEDIFF(vouchers.preaudit_date, unit_purchase_requests.date_processed) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.date_processed) + WEEKDAY(vouchers.preaudit_date) + 1, 1) as calendar_days "),

                'unit_purchase_requests.date_processed as upr_created_at',
                'unit_purchase_requests.date_processed as upr_created_at',

                'document_acceptance.days as doc_days',
                'document_acceptance.approved_date as doc_date',
                'invitation_to_bid.days as itb_days',
                'invitation_to_bid.transaction_date as itb_date',
                'pre_bid_conferences.days as prebid_days',
                'pre_bid_conferences.transaction_date as prebid_date',
                'bid_opening.days as bid_days',
                'bid_opening.transaction_date as bid_date',
                'pre_proc.days as proc_days',
                'pre_proc.pre_proc_date as proc_date',
                'post_qualification.days as pq_days',
                'post_qualification.transaction_date as pq_date',
                'request_for_quotations.days as rfq_days',
                'request_for_quotations.close_days as rfq_close_days',
                'request_for_quotations.transaction_date as rfq_created_at',
                'request_for_quotations.completed_at as rfq_completed_at',
                'invitation_for_quotation.transaction_date as ispq_transaction_date',

                DB::raw(" (select philgeps_posting.days from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where philgeps_posting.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed <= $dateTo order by philgeps_posting.created_at desc limit 1) as pp_days "),

                DB::raw(" (select philgeps_posting.philgeps_posting from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id  where philgeps_posting.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed <= $dateTo order by philgeps_posting.created_at desc limit 1) as pp_completed_at "),

                'canvassing.canvass_date as canvass_start_date',
                'canvassing.days as canvass_days',
                'notice_of_awards.days as noa_days',
                'notice_of_awards.approved_days as noa_approved_days',
                'notice_of_awards.received_days as noa_received_days',
                'notice_of_awards.awarded_date as noa_award_date',
                'notice_of_awards.accepted_date as noa_approved_date',
                'notice_of_awards.award_accepted_date as noa_award_accepted_date',
                'purchase_orders.days as po_days',
                'purchase_orders.funding_days as po_funding_days',
                'purchase_orders.mfo_days as po_mfo_days',
                'purchase_orders.bid_amount',
                'purchase_orders.coa_days as po_coa_days',
                'purchase_orders.purchase_date as po_create_date',
                'purchase_orders.mfo_received_date',
                'purchase_orders.funding_received_date',
                'purchase_orders.coa_approved_date',
                'purchase_orders.delivery_terms',
                'notice_to_proceed.days as ntp_days',
                'notice_to_proceed.accepted_days as ntp_accepted_days',
                'notice_to_proceed.prepared_date as ntp_date',
                'notice_to_proceed.award_accepted_date as ntp_award_date',


                DB::raw(" (select delivery_orders.days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed <= $dateTo order by delivery_orders.created_at desc limit 1) as dr_days "),

                DB::raw(" (select delivery_orders.delivery_days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed <= $dateTo order by delivery_orders.created_at desc limit 1) as dr_delivery_days "),

                DB::raw(" (select delivery_orders.dr_coa_days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed <= $dateTo order by delivery_orders.created_at desc limit 1) as dr_dr_coa_days "),

                DB::raw(" (select delivery_orders.transaction_date from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed <= $dateTo order by delivery_orders.created_at desc limit 1) as dr_date "),

                DB::raw(" (select delivery_orders.delivery_date from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed <= $dateTo order by delivery_orders.created_at desc limit 1) as dr_date "),

                DB::raw(" (select delivery_orders.date_delivered_to_coa from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id  AND YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND unit_purchase_requests.date_processed <= $dateTo order by delivery_orders.created_at desc limit 1) as dr_coa_date "),
                'inspection_acceptance_report.days as dr_inspection_days',
                'inspection_acceptance_report.accept_days as dr_inspection_accept_days',
                'inspection_acceptance_report.inspection_date as dr_inspection',
                'inspection_acceptance_report.accepted_date as iar_accepted_date',
                'delivery_inspection.days as di_days',
                'delivery_inspection.close_days as di_close_days',
                'delivery_inspection.closed_date as di_close',
                'delivery_inspection.start_date as di_start',
                'vouchers.days as vou_days',
                'vouchers.preaudit_days as vou_preaudit_days',
                'vouchers.jev_days as vou_jev_days',
                'vouchers.certify_days as vou_certify_days',
                'vouchers.check_days as vou_check_days',
                'vouchers.approved_days as vou_approved_days',
                'vouchers.released_days as vou_released_days',
                'vouchers.created_at as vou_start',
                'vouchers.transaction_date as v_transaction_date',
                'vouchers.preaudit_date as preaudit_date',
                'vouchers.certify_date as certify_date',
                'vouchers.journal_entry_date as journal_entry_date',
                'vouchers.approval_date as vou_approval_date',
                'vouchers.payment_release_date as vou_release',
            ]);
        }
        else{
          $model  =   $model->select([
              'unit_purchase_requests.id',
              'procurement_centers.name',
              'catered_units.short_code',
              'unit_purchase_requests.chargeability',
              'unit_purchase_requests.units',
              'unit_purchase_requests.project_name',
              'unit_purchase_requests.mode_of_procurement',
              'unit_purchase_requests.upr_number',
              'unit_purchase_requests.ref_number',
              'unit_purchase_requests.date_processed',
              'unit_purchase_requests.state',
              'unit_purchase_requests.total_amount',
              'unit_purchase_requests.date_processed',
              DB::raw("5 * (DATEDIFF(vouchers.preaudit_date, unit_purchase_requests.date_processed) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.date_processed) + WEEKDAY(vouchers.preaudit_date) + 1, 1) as calendar_days"),
              'unit_purchase_requests.date_processed as upr_created_at',
              'unit_purchase_requests.date_processed as upr_created_at',

              'document_acceptance.days as doc_days',
              'document_acceptance.approved_date as doc_date',
              'invitation_to_bid.days as itb_days',
              'invitation_to_bid.transaction_date as itb_date',
              'pre_bid_conferences.days as prebid_days',
              'pre_bid_conferences.transaction_date as prebid_date',
              'bid_opening.days as bid_days',
              'bid_opening.transaction_date as bid_date',
              'pre_proc.days as proc_days',
              'pre_proc.pre_proc_date as proc_date',
              'post_qualification.days as pq_days',
              'post_qualification.transaction_date as pq_date',
              'request_for_quotations.days as rfq_days',
              'request_for_quotations.close_days as rfq_close_days',
              'request_for_quotations.transaction_date as rfq_created_at',
              'request_for_quotations.completed_at as rfq_completed_at',
              'invitation_for_quotation.transaction_date as ispq_transaction_date',

              DB::raw(" (select philgeps_posting.days from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND philgeps_posting.upr_id = unit_purchase_requests.id order by philgeps_posting.created_at desc limit 1) as pp_days "),

              DB::raw(" (select philgeps_posting.philgeps_posting from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id  where YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom' AND philgeps_posting.upr_id = unit_purchase_requests.id order by philgeps_posting.created_at desc limit 1) as pp_completed_at "),

              'canvassing.canvass_date as canvass_start_date',
              'canvassing.days as canvass_days',
              'notice_of_awards.days as noa_days',
              'notice_of_awards.approved_days as noa_approved_days',
              'notice_of_awards.received_days as noa_received_days',
              'notice_of_awards.awarded_date as noa_award_date',
              'notice_of_awards.accepted_date as noa_approved_date',
              'notice_of_awards.award_accepted_date as noa_award_accepted_date',
              'purchase_orders.days as po_days',
              'purchase_orders.funding_days as po_funding_days',
              'purchase_orders.mfo_days as po_mfo_days',
              'purchase_orders.coa_days as po_coa_days',
              'purchase_orders.purchase_date as po_create_date',
              'purchase_orders.mfo_received_date',
              'purchase_orders.bid_amount',
              'purchase_orders.funding_received_date',
              'purchase_orders.coa_approved_date',
              'purchase_orders.delivery_terms',
              'notice_to_proceed.days as ntp_days',
              'notice_to_proceed.accepted_days as ntp_accepted_days',
              'notice_to_proceed.prepared_date as ntp_date',
              'notice_to_proceed.award_accepted_date as ntp_award_date',


              DB::raw(" (select delivery_orders.days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id order by delivery_orders.created_at desc limit 1) as dr_days "),

              DB::raw(" (select delivery_orders.delivery_days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id order by delivery_orders.created_at desc limit 1) as dr_delivery_days "),

              DB::raw(" (select delivery_orders.dr_coa_days from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id order by delivery_orders.created_at desc limit 1) as dr_dr_coa_days "),

              DB::raw(" (select delivery_orders.transaction_date from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id order by delivery_orders.created_at desc limit 1) as dr_date "),

              DB::raw(" (select delivery_orders.delivery_date from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id order by delivery_orders.created_at desc limit 1) as dr_date "),

              DB::raw(" (select delivery_orders.date_delivered_to_coa from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id  where delivery_orders.upr_id = unit_purchase_requests.id order by delivery_orders.created_at desc limit 1) as dr_coa_date "),
              'inspection_acceptance_report.days as dr_inspection_days',
              'inspection_acceptance_report.accept_days as dr_inspection_accept_days',
              'inspection_acceptance_report.inspection_date as dr_inspection',
              'inspection_acceptance_report.accepted_date as iar_accepted_date',
              'delivery_inspection.days as di_days',
              'delivery_inspection.close_days as di_close_days',
              'delivery_inspection.closed_date as di_close',
              'delivery_inspection.start_date as di_start',
              'vouchers.days as vou_days',
              'vouchers.preaudit_days as vou_preaudit_days',
              'vouchers.jev_days as vou_jev_days',
              'vouchers.certify_days as vou_certify_days',
              'vouchers.check_days as vou_check_days',
              'vouchers.approved_days as vou_approved_days',
              'vouchers.released_days as vou_released_days',
              'vouchers.created_at as vou_start',
              'vouchers.transaction_date as v_transaction_date',
              'vouchers.preaudit_date as preaudit_date',
              'vouchers.certify_date as certify_date',
              'vouchers.journal_entry_date as journal_entry_date',
              'vouchers.approval_date as vou_approval_date',
              'vouchers.payment_release_date as vou_release',
          ]);
        }


        $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('ispq_quotations', 'ispq_quotations.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('invitation_for_quotation', 'invitation_for_quotation.id', '=', 'ispq_quotations.ispq_id');
        $model  =   $model->leftJoin('canvassing', 'canvassing.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('notice_of_awards', 'notice_of_awards.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('notice_to_proceed', 'notice_to_proceed.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('inspection_acceptance_report', 'inspection_acceptance_report.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('delivery_inspection', 'delivery_inspection.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('vouchers', 'vouchers.upr_id', '=', 'unit_purchase_requests.id');

        // Biddings
        $model  =   $model->leftJoin('document_acceptance', 'document_acceptance.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('pre_proc', 'pre_proc.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('invitation_to_bid', 'invitation_to_bid.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('pre_bid_conferences', 'pre_bid_conferences.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('bid_opening', 'bid_opening.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('post_qualification', 'post_qualification.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('catered_units', 'catered_units.id', '=', 'unit_purchase_requests.units');

        $model  =   $model->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'unit_purchase_requests.procurement_office');

        $model  =   $model->groupBy([
            'catered_units.short_code',
            'procurement_centers.name',
            'unit_purchase_requests.id',
            'unit_purchase_requests.units',
            'unit_purchase_requests.chargeability',
            'unit_purchase_requests.project_name',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.mode_of_procurement',
            'unit_purchase_requests.ref_number',
            'unit_purchase_requests.date_processed',
            'unit_purchase_requests.state',
            'unit_purchase_requests.total_amount',
            'unit_purchase_requests.date_processed',
            'document_acceptance.days',
            'document_acceptance.approved_date',
            'pre_proc.days',
            'pre_proc.pre_proc_date',
            'invitation_to_bid.days',
            'invitation_to_bid.transaction_date',
            'bid_opening.days',
            'bid_opening.transaction_date',
            'post_qualification.days',
            'post_qualification.transaction_date',
            'pre_bid_conferences.days',
            'pre_bid_conferences.transaction_date',
            'request_for_quotations.days',
            'request_for_quotations.close_days',
            'request_for_quotations.transaction_date',
            'request_for_quotations.completed_at',
            'invitation_for_quotation.transaction_date',
            'canvassing.canvass_date',
            'canvassing.days',
            'notice_of_awards.days',
            'notice_of_awards.approved_days',
            'notice_of_awards.received_days',
            'notice_of_awards.awarded_date',
            'notice_of_awards.accepted_date',
            'notice_of_awards.award_accepted_date',
            'purchase_orders.purchase_date',
            'purchase_orders.mfo_received_date',
            'purchase_orders.funding_received_date',
            'purchase_orders.coa_approved_date',
            'purchase_orders.days',
            'purchase_orders.funding_days',
            'purchase_orders.delivery_terms',
            'purchase_orders.mfo_days',
            'purchase_orders.coa_days',
            'purchase_orders.bid_amount',
            'notice_to_proceed.days',
            'notice_to_proceed.accepted_days',
            'notice_to_proceed.prepared_date',
            'notice_to_proceed.award_accepted_date',
            'inspection_acceptance_report.inspection_date',
            'inspection_acceptance_report.accepted_date',
            'inspection_acceptance_report.days',
            'inspection_acceptance_report.accept_days',
            'delivery_inspection.days',
            'delivery_inspection.close_days',
            'delivery_inspection.closed_date',
            'delivery_inspection.start_date',
            'vouchers.days',
            'vouchers.preaudit_days',
            'vouchers.jev_days',
            'vouchers.certify_days',
            'vouchers.check_days',
            'vouchers.approved_days',
            'vouchers.released_days',
            'vouchers.received_days',
            'vouchers.created_at',
            'vouchers.transaction_date',
            'vouchers.preaudit_date',
            'vouchers.certify_date',
            'vouchers.journal_entry_date',
            'vouchers.approval_date',
            'vouchers.payment_release_date',
            // 'vouchers.payment_received_date',
        ]);


        if($request->has('date_from') != null)
        {
            $model  =   $model->where('unit_purchase_requests.date_processed', '>=', $request->get('date_from'));
        }

        if($request->has('date_to') != null)
        {
            $model  =   $model->where('unit_purchase_requests.date_processed', '<=', $request->get('date_to'));
        }

      $model  =   $model->whereRaw("YEAR(unit_purchase_requests.date_processed) <= '$yearto' AND YEAR(unit_purchase_requests.date_processed) >= '$yearfrom'");
        if($request->has('type') == null || $request->get('type') == 'alternative')
        {
            $model      =   $model->where('mode_of_procurement','!=', 'public_bidding');
        }
        else
        {
            $model      =   $model->where('mode_of_procurement','=', 'public_bidding');
        }

        if($search != null)
        {
            $model  =   $model->where('unit_purchase_requests.upr_number', 'LIKE', "%$search%");
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


        return $model->get();
    }

    /**
     * [getProcurementDatatable description]
     *
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    public function getProcurementDatatable($request)
    {
        $model      =   $this->getTransactionDay($request);

        return $this->dataTable($model);
    }

    /**
     * [getPSRDatatable description]
     *
     * @return [type] [description]
     */
    public function getTransactionDayDatatable($request)
    {
        $model      =   $this->getTransactionDay($request);

        return $this->dataTable($model);
    }

}