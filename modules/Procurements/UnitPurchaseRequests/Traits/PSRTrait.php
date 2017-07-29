<?php

namespace Revlv\Procurements\UnitPurchaseRequests\Traits;

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
    public function getPSR($request, $search = null)
    {
        $model  =   $this->model;

        if($request->has('type') == null)
        {
            $model  =   $model->select([
                'procurement_centers.name as unit_name',
                DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id  ) as philgeps "),
                DB::raw("COUNT(unit_purchase_requests.id) as upr"),
                DB::raw("COUNT(request_for_quotations.id) as rfq"),
                DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
                // DB::raw("COUNT(philgeps_posting.id) as philgeps"),
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
                DB::raw("COUNT(document_acceptance.id) as doc"),
                // DB::raw("COUNT(invitation_to_bid.id) as itb"),

                DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id  ) as itb "),

                DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id  ) as prebid "),

                DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id  ) as bidop "),

                DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id  ) as pq "),
            ]);
        }
        else
        {

            $model  =   $model->select([
                'procurement_centers.name as unit_name',
                DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id  ) as philgeps "),
                DB::raw("COUNT(unit_purchase_requests.id) as upr"),
                DB::raw("COUNT(request_for_quotations.id) as rfq"),
                DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
                // DB::raw("COUNT(philgeps_posting.id) as philgeps"),
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
                DB::raw("COUNT(document_acceptance.id) as doc"),
                // DB::raw("COUNT(invitation_to_bid.id) as itb"),

                DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id  ) as itb "),

                DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id  ) as prebid "),

                DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id  ) as bidop "),

                DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id  ) as pq "),
            ]);
        }

        $model  =   $model->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'unit_purchase_requests.procurement_office');
        $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.upr_id', '=', 'unit_purchase_requests.id');

        // $model  =   $model->leftJoin('philgeps_posting', 'philgeps_posting.upr_id', '=', 'unit_purchase_requests.id');
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

        // Biddings
        $model  =   $model->leftJoin('document_acceptance', 'document_acceptance.upr_id', '=', 'unit_purchase_requests.id');
        // $model  =   $model->leftJoin('invitation_to_bid', 'invitation_to_bid.upr_id', '=', 'unit_purchase_requests.id');
        // $model  =   $model->leftJoin('pre_bid_conferences', 'pre_bid_conferences.upr_id', '=', 'unit_purchase_requests.id');
        // $model  =   $model->leftJoin('bid_opening', 'bid_opening.upr_id', '=', 'unit_purchase_requests.id');
        // $model  =   $model->leftJoin('post_qualification', 'post_qualification.upr_id', '=', 'unit_purchase_requests.id');


        if($request->has('type') == null)
        {
            $model      =   $model->where('mode_of_procurement','!=', 'public_bidding');
        }
        else
        {
            $model      =   $model->where('mode_of_procurement','=', 'public_bidding');
        }

        $model  =   $model->groupBy([
            'procurement_centers.name',
            'procurement_centers.id'
        ]);

        return $model->get();
    }

    /**
     *
     *
     * @param  [type] $search [description]
     * @return [type]         [description]
     */
    public function getPSRUnits($request, $search = null)
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
            DB::raw("COUNT(document_acceptance.id) as doc"),
            DB::raw("COUNT(invitation_to_bid.id) as itb"),
            DB::raw("COUNT(pre_bid_conferences.id) as prebid"),
            DB::raw("COUNT(bid_opening.id) as bidop"),
            DB::raw("COUNT(post_qualification.id) as pq"),
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


        // Biddings
        $model  =   $model->leftJoin('document_acceptance', 'document_acceptance.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('invitation_to_bid', 'invitation_to_bid.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('pre_bid_conferences', 'pre_bid_conferences.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('bid_opening', 'bid_opening.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('post_qualification', 'post_qualification.upr_id', '=', 'unit_purchase_requests.id');

        if($request->has('type') == null)
        {
            $model      =   $model->where('mode_of_procurement','!=', 'public_bidding');
        }
        else
        {
            $model      =   $model->where('mode_of_procurement','=', 'public_bidding');
        }

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
    public function getPSRDatatable($request)
    {
        $model      =   $this->getPSR($request);

        return $this->dataTable($model);
    }

}