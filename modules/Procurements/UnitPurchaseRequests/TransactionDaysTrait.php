<?php

namespace Revlv\Procurements\UnitPurchaseRequests;

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
    public function getTransactionDay($search = null)
    {
        $model  =    $this->model;

        $model  =   $model->select([
            'unit_purchase_requests.id',
            'unit_purchase_requests.project_name',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.ref_number',
            'unit_purchase_requests.date_prepared',
            'unit_purchase_requests.state',
            'unit_purchase_requests.date_processed',
            'unit_purchase_requests.created_at as upr_created_at',
            'request_for_quotations.id as rfq_id',
            'request_for_quotations.rfq_number',
            'request_for_quotations.status as rfq_status',
            'request_for_quotations.created_at as rfq_created_at',
            'request_for_quotations.completed_at as rfq_completed_at',
            'philgeps_posting.id as pp_id',
            'philgeps_posting.philgeps_posting as pp_completed_at',
            'invitation_for_quotation.transaction_date as ispq_transaction_date',
            'canvassing.created_at as canvass_start_date',
            'notice_of_awards.awarded_date as noa_award_date',
            'notice_of_awards.accepted_date as noa_approved_date',
            'notice_of_awards.award_accepted_date as noa_award_accepted_date',
            'purchase_orders.created_at as po_create_date',
            'purchase_orders.mfo_released_date',
            'purchase_orders.pcco_released_date',
            'purchase_orders.coa_approved_date',
            'notice_to_proceed.prepared_date as ntp_date',
            'notice_to_proceed.award_accepted_date as ntp_award_date',
            'delivery_orders.created_at as dr_date',
            'delivery_orders.delivery_date',
            'delivery_orders.date_delivered_to_coa as dr_coa_date',
            'inspection_acceptance_report.accepted_date as dr_inspection',
            'delivery_inspection.closed_date as di_close',
            'delivery_inspection.start_date as di_start',
            'vouchers.created_at as vou_start',
            'vouchers.payment_release_date as vou_release',
            'vouchers.payment_received_date as vou_received',
        ]);

        $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('philgeps_posting', 'philgeps_posting.upr_id', '=', 'unit_purchase_requests.id');
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


        return $model->get();
    }

    /**
     * [getPSRDatatable description]
     *
     * @return [type] [description]
     */
    public function getTransactionDayDatatable()
    {
        $model      =   $this->getTransactionDay();

        return $this->dataTable($model);
    }

}