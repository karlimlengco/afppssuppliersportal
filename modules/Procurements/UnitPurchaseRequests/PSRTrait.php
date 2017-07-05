<?php

namespace Revlv\Procurements\UnitPurchaseRequests;

use Illuminate\Http\Request;
use DB;
use Datatables;

trait PSRTrait
{

    /**
     * [getDatatable description]
     *
     * @param  [int]    $company_id ['company id ']
     * @return [type]               [description]
     */
    public function getPSR($search = null)
    {
        $model  =   $this->model;

        $model  =   $model->select([
            'procurement_centers.name as unit_name',
            DB::raw("COUNT(unit_purchase_requests.id) as upr"),
            DB::raw("COUNT(request_for_quotations.id) as rfq"),
            DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
            DB::raw("COUNT(philgeps_posting.id) as philgeps"),
            DB::raw("COUNT(invitation_for_quotation.id) as ispq"),
            DB::raw("COUNT(canvassing.id) as canvass"),
            DB::raw("COUNT(notice_of_awards.id) as noa"),
            DB::raw("COUNT(notice_of_awards.award_accepted_date) as noaa"),
            DB::raw("COUNT(purchase_orders.id) as po"),
            DB::raw("COUNT(purchase_orders.mfo_released_date) as po_mfo_released"),
            DB::raw("COUNT(purchase_orders.mfo_received_date) as po_mfo_received"),
            DB::raw("COUNT(purchase_orders.funding_released_date) as po_pcco_released"),
            DB::raw("COUNT(purchase_orders.funding_received_date) as po_pcco_received"),
            DB::raw("COUNT(purchase_orders.coa_approved_date) as po_coa_approved"),
            DB::raw("COUNT(notice_to_proceed.id) as ntp"),
            DB::raw("COUNT(notice_to_proceed.award_accepted_date) as ntpa"),
            DB::raw("COUNT(delivery_orders.id) as nod"),
            DB::raw("COUNT(delivery_orders.delivery_date) as delivery"),
            DB::raw("COUNT(inspection_acceptance_report.id) as tiac"),
            DB::raw("COUNT(inspection_acceptance_report.accepted_date) as coa_inspection"),
            DB::raw("COUNT(delivery_inspection.id) as diir"),
            DB::raw("COUNT(vouchers.id) as voucher"),
            DB::raw("COUNT(vouchers.id) as end_process"),
        ]);

        $model  =   $model->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'unit_purchase_requests.place_of_delivery');
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
        // $model  =   $model->leftJoin('rfq_proponents', 'rfq_proponents.rfq_id', '=', 'request_for_quotations.id');
        $model  =   $model->leftJoin('vouchers', 'vouchers.upr_id', '=', 'unit_purchase_requests.id');


        $model  =   $model->groupBy([
            'procurement_centers.name'
        ]);

        return $model->get();
    }

    /**
     *
     *
     * @param  [type] $search [description]
     * @return [type]         [description]
     */
    public function getPSRUnits($search = null)
    {

        $model  =   $this->model;

        $model  =   $model->select([
            'units.name as unit_name',
            DB::raw("COUNT(unit_purchase_requests.id) as upr"),
            DB::raw("COUNT(request_for_quotations.id) as rfq"),
            DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
            DB::raw("COUNT(philgeps_posting.id) as philgeps"),
            DB::raw("COUNT(invitation_for_quotation.id) as ispq"),
            DB::raw("COUNT(canvassing.id) as canvass"),
            DB::raw("COUNT(notice_of_awards.id) as noa"),
            DB::raw("COUNT(notice_of_awards.award_accepted_date) as noaa"),
            DB::raw("COUNT(purchase_orders.id) as po"),
            DB::raw("COUNT(purchase_orders.mfo_released_date) as po_mfo_released"),
            DB::raw("COUNT(purchase_orders.mfo_received_date) as po_mfo_received"),
            DB::raw("COUNT(purchase_orders.funding_released_date) as po_pcco_released"),
            DB::raw("COUNT(purchase_orders.funding_received_date) as po_pcco_received"),
            DB::raw("COUNT(purchase_orders.coa_approved_date) as po_coa_approved"),
            DB::raw("COUNT(notice_to_proceed.id) as ntp"),
            DB::raw("COUNT(notice_to_proceed.award_accepted_date) as ntpa"),
            DB::raw("COUNT(delivery_orders.id) as nod"),
            DB::raw("COUNT(delivery_orders.delivery_date) as delivery"),
            DB::raw("COUNT(inspection_acceptance_report.id) as tiac"),
            DB::raw("COUNT(inspection_acceptance_report.accepted_date) as coa_inspection"),
            DB::raw("COUNT(delivery_inspection.id) as diir"),
            DB::raw("COUNT(vouchers.id) as voucher"),
            DB::raw("COUNT(vouchers.payment_received_date) as end_process"),
        ]);

        $model  =   $model->leftJoin('units', 'units.id', '=', 'unit_purchase_requests.units');
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
        // $model  =   $model->leftJoin('rfq_proponents', 'rfq_proponents.rfq_id', '=', 'request_for_quotations.id');
        $model  =   $model->leftJoin('vouchers', 'vouchers.upr_id', '=', 'unit_purchase_requests.id');


        $model  =   $model->groupBy([
            'units.name'
        ]);

        return $model->get();
    }

    /**
     * [getPSRDatatable description]
     *
     * @return [type] [description]
     */
    public function getPSRDatatable()
    {
        $model      =   $this->getPSR();

        return $this->dataTable($model);
    }

}