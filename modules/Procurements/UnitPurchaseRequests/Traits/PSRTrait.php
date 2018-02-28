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
    public function getPcooItemPSRDatatable($request = null, $search = null)
    {
          $model  =   $this->model;
          $dateTo = $request->get('date_to');
          $dateFrom = $request->get('date_from');

          $date    = \Carbon\Carbon::now();
          $yearto    = $date->format('Y');
          $yearfrom    = $date->format('Y');
          // dd($request->all());

          $selected = [
              'procurement_centers.name as unit_name',
              DB::raw("MIN(unit_purchase_requests.date_prepared) as date_prepared "),
              DB::raw("COUNT(unit_purchase_requests.id) as upr"),
              DB::raw("COUNT(request_for_quotations.id) as rfq"),
              DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
              DB::raw("COUNT(invitation_for_quotation.id) as ispq"),
              DB::raw("COUNT(canvassing.id) as canvass"),
              DB::raw("COUNT(notice_of_awards.id) as noa"),
              DB::raw("COUNT(notice_of_awards.award_accepted_date) as noaa"),
              DB::raw("COUNT(notice_of_awards.accepted_date) as noar"),
              DB::raw("COUNT(purchase_orders.id) as po"),
              DB::raw("COUNT(purchase_orders.mfo_released_date) as po_mfo_released"),
              DB::raw("COUNT(purchase_orders.mfo_received_date) as po_mfo_received"),
              DB::raw("COUNT(purchase_orders.funding_released_date) as po_pcco_released"),
              DB::raw("COUNT(purchase_orders.funding_received_date) as po_pcco_received"),
              DB::raw("COUNT(purchase_orders.coa_approved_date) as po_coa_approved"),
              DB::raw("COUNT(notice_to_proceed.id) as ntp"),
              DB::raw("COUNT(notice_to_proceed.award_accepted_date) as ntpa"),
              DB::raw("COUNT(inspection_acceptance_report.id) as tiac"),
              DB::raw("COUNT(inspection_acceptance_report.accepted_date) as coa_inspection"),
              DB::raw("COUNT(delivery_inspection.id) as diir"),
              DB::raw("COUNT(delivery_inspection.start_date) as diir_start"),
              DB::raw("COUNT(delivery_inspection.closed_date) as diir_close"),
              DB::raw("COUNT(vouchers.id) as voucher"),
              DB::raw("COUNT(vouchers.id) as end_process"),
              DB::raw("COUNT(vouchers.payment_release_date) as ldad"),
          ];

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

          if($dateTo != null && $dateFrom != null && $search != null )
          {
              if($request->has('type') == null){

                array_push($selected, DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom') as philgeps "));


                array_push($selected, DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as nod "));

                array_push($selected, DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as delivery "));

                array_push($selected, DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as date_delivered_to_coa "));

                array_push($selected, DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as pre_proc "));

                array_push($selected, DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as doc ") );

                array_push($selected, DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as itb "));

                array_push($selected, DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as prebid "));


                array_push($selected, DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as bidop "));


                array_push($selected, DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as pq "));


              }else{


                  array_push($selected, DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom') as philgeps "));


                  array_push($selected, DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as nod "));

                  array_push($selected, DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as delivery "));

                  array_push($selected, DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as date_delivered_to_coa "));

                  array_push($selected, DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as pre_proc "));

                  array_push($selected, DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as doc ") );

                  array_push($selected, DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as itb "));

                  array_push($selected, DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as prebid "));


                  array_push($selected, DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as bidop "));


                  array_push($selected, DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as pq "));
              }
          } elseif($dateTo != null && $dateFrom != null)
          {
              if($request->has('type') == null){

                array_push($selected, DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as philgeps "));


                array_push($selected, DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as nod "));

                array_push($selected, DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as delivery "));

                array_push($selected, DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as date_delivered_to_coa "));

                array_push($selected, DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as pre_proc "));

                array_push($selected, DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as doc ") );

                array_push($selected, DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as itb "));

                array_push($selected, DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as prebid "));


                array_push($selected, DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as bidop "));


                array_push($selected, DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as pq "));


              }else{


                  array_push($selected, DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as philgeps "));


                  array_push($selected, DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as nod "));

                  array_push($selected, DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as delivery "));

                  array_push($selected, DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as date_delivered_to_coa "));

                  array_push($selected, DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as pre_proc "));

                  array_push($selected, DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as doc ") );

                  array_push($selected, DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as itb "));

                  array_push($selected, DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as prebid "));


                  array_push($selected, DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as bidop "));


                  array_push($selected, DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as pq "));
              }
          } elseif($dateTo != null && $dateFrom == null)
          {
              if($request->has('type') == null){

                array_push($selected, DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as philgeps "));


                array_push($selected, DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as nod "));

                array_push($selected, DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as delivery "));

                array_push($selected, DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as date_delivered_to_coa "));

                array_push($selected, DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as pre_proc "));

                array_push($selected, DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as doc ") );

                array_push($selected, DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as itb "));

                array_push($selected, DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as prebid "));


                array_push($selected, DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as bidop "));


                array_push($selected, DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as pq "));


              }else{


                  array_push($selected, DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as philgeps "));


                  array_push($selected, DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as nod "));

                  array_push($selected, DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as delivery "));

                  array_push($selected, DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as date_delivered_to_coa "));

                  array_push($selected, DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as pre_proc "));

                  array_push($selected, DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as doc ") );

                  array_push($selected, DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as itb "));

                  array_push($selected, DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as prebid "));


                  array_push($selected, DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as bidop "));


                  array_push($selected, DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as pq "));
              }
          } elseif($dateTo == null && $dateFrom != null)
          {
              if($request->has('type') == null){

                array_push($selected, DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as philgeps "));


                array_push($selected, DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as nod "));

                array_push($selected, DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as delivery "));

                array_push($selected, DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as date_delivered_to_coa "));

                array_push($selected, DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as pre_proc "));

                array_push($selected, DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as doc ") );

                array_push($selected, DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as itb "));

                array_push($selected, DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as prebid "));


                array_push($selected, DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as bidop "));


                array_push($selected, DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as pq "));


              }else{


                  array_push($selected, DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as philgeps "));


                  array_push($selected, DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as nod "));

                  array_push($selected, DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as delivery "));

                  array_push($selected, DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as date_delivered_to_coa "));

                  array_push($selected, DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as pre_proc "));

                  array_push($selected, DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as doc ") );

                  array_push($selected, DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as itb "));

                  array_push($selected, DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as prebid "));


                  array_push($selected, DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as bidop "));


                  array_push($selected, DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as pq "));
              }
          } else {
              if($request->get('type') != 'bidding'){

                array_push($selected, DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as philgeps "));


                array_push($selected, DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as nod "));

                array_push($selected, DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as delivery "));

                array_push($selected, DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as date_delivered_to_coa "));

                array_push($selected, DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as pre_proc "));

                array_push($selected, DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as doc ") );

                array_push($selected, DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as itb "));

                array_push($selected, DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as prebid "));


                array_push($selected, DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as bidop "));


                array_push($selected, DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as pq "));


              }else{


                  array_push($selected, DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as philgeps "));


                  array_push($selected, DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as nod "));

                  array_push($selected, DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as delivery "));

                  array_push($selected, DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as date_delivered_to_coa "));

                  array_push($selected, DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as pre_proc "));

                  array_push($selected, DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as doc ") );

                  array_push($selected, DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as itb "));

                  array_push($selected, DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as prebid "));


                  array_push($selected, DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as bidop "));


                  array_push($selected, DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as pq "));
              }
          }

          $model  = $model->select($selected);

          $model  =   $model->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'unit_purchase_requests.procurement_office');
          $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.upr_id', '=', 'unit_purchase_requests.id');
          $model  =   $model->leftJoin('ispq_quotations', 'ispq_quotations.upr_id', '=', 'unit_purchase_requests.id');
          $model  =   $model->leftJoin('canvassing', 'canvassing.upr_id', '=', 'unit_purchase_requests.id');
          $model  =   $model->leftJoin('notice_of_awards', 'notice_of_awards.upr_id', '=', 'unit_purchase_requests.id');
          $model  =   $model->leftJoin('invitation_for_quotation', 'invitation_for_quotation.id', '=', 'ispq_quotations.ispq_id');
          $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id');
          $model  =   $model->leftJoin('notice_to_proceed', 'notice_to_proceed.upr_id', '=', 'unit_purchase_requests.id');
          $model  =   $model->leftJoin('inspection_acceptance_report', 'inspection_acceptance_report.upr_id', '=', 'unit_purchase_requests.id');
          $model  =   $model->leftJoin('delivery_inspection', 'delivery_inspection.upr_id', '=', 'unit_purchase_requests.id');
          $model  =   $model->leftJoin('vouchers', 'vouchers.upr_id', '=', 'unit_purchase_requests.id');


        if($request != null)
        {

            if($request->has('date_from') != null)
            {
                $model  =   $model->where('unit_purchase_requests.date_prepared', '>=', $request->get('date_from'));
            }

            if($request->has('date_to') != null)
            {
                $model  =   $model->where('unit_purchase_requests.date_prepared', '<=', $request->get('date_to'));
            }

            if($request->get('type') != 'bidding')
            {
                $model      =   $model->where('mode_of_procurement','!=', 'public_bidding');
            }
            else
            {
                $model      =   $model->where('mode_of_procurement','=', 'public_bidding');
            }
        }

        $model  =   $model->whereRaw("YEAR(unit_purchase_requests.date_prepared) <= '$yearto' AND YEAR(unit_purchase_requests.date_prepared) >= '$yearfrom'");

        if($search != null)
        {
            $model  =   $model->where('procurement_centers.name', 'LIKE', "%$search%");
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

        $model  = $model->whereNotNull('procurement_centers.name');

        $model  =   $model->where('unit_purchase_requests.status', '!=', 'draft');

        $model  =   $model->groupBy([
            'procurement_centers.name',
            // 'unit_purchase_requests.id',
            // 'unit_purchase_requests.date_prepared',
            'procurement_centers.id'
        ]);
        return $model->get();
    }

    /**
     * [getPcooPSRDatatable description]
     * @param  [type] $request [description]
     * @param  [type] $search  [description]
     * @return [type]          [description]
     */
    public function getPcooPSRDatatable($request = null, $search = null)
    {
          $model  =   $this->model;
          $dateTo = $request->get('date_to');
          $dateFrom = $request->get('date_from');

          $date    = \Carbon\Carbon::now();
          $yearto    = $date->format('Y');
          $yearfrom    = $date->format('Y');
          // dd($request->all());


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

          $selected = [
              'procurement_centers.name as unit_name',
              DB::raw("MIN(unit_purchase_requests.date_prepared) as date_prepared "),
              // DB::raw("(select count(unit_purchase_requests.id) from unit_purchase_requests as u where u.procurement_office = procurement_centers.id ) as upr_count"),
              DB::raw("COUNT(unit_purchase_requests.id) as upr_count"),
              DB::raw("COUNT(request_for_quotations.id) as rfq"),
              DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
              DB::raw("COUNT(invitation_for_quotation.id) as ispq"),
              DB::raw("COUNT(canvassing.id) as canvass"),
              DB::raw("COUNT(notice_of_awards.id) as noa"),
              DB::raw("COUNT(notice_of_awards.award_accepted_date) as noaa"),
              DB::raw("COUNT(notice_of_awards.accepted_date) as noar"),
              DB::raw("COUNT(purchase_orders.id) as po"),
              DB::raw("COUNT(purchase_orders.mfo_released_date) as po_mfo_released"),
              DB::raw("COUNT(purchase_orders.mfo_received_date) as po_mfo_received"),
              DB::raw("COUNT(purchase_orders.funding_released_date) as po_pcco_released"),
              DB::raw("COUNT(purchase_orders.funding_received_date) as po_pcco_received"),
              DB::raw("COUNT(purchase_orders.coa_approved_date) as po_coa_approved"),
              DB::raw("COUNT(notice_to_proceed.id) as ntp"),
              DB::raw("COUNT(notice_to_proceed.award_accepted_date) as ntpa"),
              DB::raw("COUNT(inspection_acceptance_report.id) as tiac"),
              DB::raw("COUNT(inspection_acceptance_report.accepted_date) as coa_inspection"),
              DB::raw("COUNT(delivery_inspection.id) as diir"),
              DB::raw("COUNT(delivery_inspection.start_date) as diir_start"),
              DB::raw("COUNT(delivery_inspection.closed_date) as diir_close"),
              DB::raw("COUNT(vouchers.id) as voucher"),
              DB::raw("COUNT(vouchers.id) as end_process"),
              DB::raw("COUNT(vouchers.payment_release_date) as ldad"),
          ];

          if($dateTo != null && $dateFrom != null && $search != null )
          {
              if($request->has('type') == null){

                array_push($selected, DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom') as philgeps "));


                array_push($selected, DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as nod "));

                array_push($selected, DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as delivery "));

                array_push($selected, DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as date_delivered_to_coa "));

                array_push($selected, DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as pre_proc "));

                array_push($selected, DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as doc ") );

                array_push($selected, DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as itb "));

                array_push($selected, DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as prebid "));


                array_push($selected, DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as bidop "));


                array_push($selected, DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as pq "));


              }else{


                  array_push($selected, DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom') as philgeps "));


                  array_push($selected, DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as nod "));

                  array_push($selected, DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as delivery "));

                  array_push($selected, DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as date_delivered_to_coa "));

                  array_push($selected, DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as pre_proc "));

                  array_push($selected, DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as doc ") );

                  array_push($selected, DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as itb "));

                  array_push($selected, DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as prebid "));


                  array_push($selected, DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as bidop "));


                  array_push($selected, DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as pq "));
              }
          } elseif($dateTo != null && $dateFrom != null)
          {
              if($request->has('type') == null){

                array_push($selected, DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as philgeps "));


                array_push($selected, DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as nod "));

                array_push($selected, DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as delivery "));

                array_push($selected, DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as date_delivered_to_coa "));

                array_push($selected, DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as pre_proc "));

                array_push($selected, DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as doc ") );

                array_push($selected, DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as itb "));

                array_push($selected, DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as prebid "));


                array_push($selected, DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as bidop "));


                array_push($selected, DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as pq "));


              }else{


                  array_push($selected, DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as philgeps "));


                  array_push($selected, DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as nod "));

                  array_push($selected, DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as delivery "));

                  array_push($selected, DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as date_delivered_to_coa "));

                  array_push($selected, DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as pre_proc "));

                  array_push($selected, DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as doc ") );

                  array_push($selected, DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as itb "));

                  array_push($selected, DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as prebid "));


                  array_push($selected, DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as bidop "));


                  array_push($selected, DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as pq "));
              }
          } elseif($dateTo != null && $dateFrom == null)
          {
              if($request->has('type') == null){

                array_push($selected, DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as philgeps "));


                array_push($selected, DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as nod "));

                array_push($selected, DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as delivery "));

                array_push($selected, DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as date_delivered_to_coa "));

                array_push($selected, DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as pre_proc "));

                array_push($selected, DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as doc ") );

                array_push($selected, DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as itb "));

                array_push($selected, DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as prebid "));


                array_push($selected, DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as bidop "));


                array_push($selected, DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as pq "));


              }else{


                  array_push($selected, DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as philgeps "));


                  array_push($selected, DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as nod "));

                  array_push($selected, DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as delivery "));

                  array_push($selected, DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as date_delivered_to_coa "));

                  array_push($selected, DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as pre_proc "));

                  array_push($selected, DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as doc ") );

                  array_push($selected, DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as itb "));

                  array_push($selected, DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as prebid "));


                  array_push($selected, DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as bidop "));


                  array_push($selected, DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared <= $dateTo AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'    ) as pq "));
              }
          } elseif($dateTo == null && $dateFrom != null)
          {
              if($request->has('type') == null){

                array_push($selected, DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as philgeps "));


                array_push($selected, DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as nod "));

                array_push($selected, DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as delivery "));

                array_push($selected, DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as date_delivered_to_coa "));

                array_push($selected, DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as pre_proc "));

                array_push($selected, DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as doc ") );

                array_push($selected, DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as itb "));

                array_push($selected, DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as prebid "));


                array_push($selected, DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as bidop "));


                array_push($selected, DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as pq "));


              }else{


                  array_push($selected, DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as philgeps "));


                  array_push($selected, DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as nod "));

                  array_push($selected, DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as delivery "));

                  array_push($selected, DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as date_delivered_to_coa "));

                  array_push($selected, DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as pre_proc "));

                  array_push($selected, DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as doc ") );

                  array_push($selected, DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as itb "));

                  array_push($selected, DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as prebid "));


                  array_push($selected, DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as bidop "));


                  array_push($selected, DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND upr.date_prepared >= $dateFrom AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'   ) as pq "));
              }
          } else {
              if($request->get('type') != 'bidding'){

                array_push($selected, DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as philgeps "));


                array_push($selected, DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as nod "));

                array_push($selected, DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as delivery "));

                array_push($selected, DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as date_delivered_to_coa "));

                array_push($selected, DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as pre_proc "));

                array_push($selected, DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as doc ") );

                array_push($selected, DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as itb "));

                array_push($selected, DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as prebid "));


                array_push($selected, DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as bidop "));


                array_push($selected, DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as pq "));


              }else{


                  array_push($selected, DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom' ) as philgeps "));


                  array_push($selected, DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as nod "));

                  array_push($selected, DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as delivery "));

                  array_push($selected, DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as date_delivered_to_coa "));

                  array_push($selected, DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as pre_proc "));

                  array_push($selected, DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as doc ") );

                  array_push($selected, DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as itb "));

                  array_push($selected, DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as prebid "));


                  array_push($selected, DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as bidop "));


                  array_push($selected, DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND YEAR(upr.date_prepared) <= '$yearto' AND YEAR(upr.date_prepared) >= '$yearfrom'  ) as pq "));
              }
          }

          $model  = $model->select($selected);

          $model  =   $model->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'unit_purchase_requests.procurement_office');
          $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.upr_id', '=', 'unit_purchase_requests.id');
          $model  =   $model->leftJoin('ispq_quotations', 'ispq_quotations.upr_id', '=', 'unit_purchase_requests.id');
          $model  =   $model->leftJoin('canvassing', 'canvassing.upr_id', '=', 'unit_purchase_requests.id');
          $model  =   $model->leftJoin('notice_of_awards', 'notice_of_awards.upr_id', '=', 'unit_purchase_requests.id');
          $model  =   $model->leftJoin('invitation_for_quotation', 'invitation_for_quotation.id', '=', 'ispq_quotations.ispq_id');
          $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id');
          $model  =   $model->leftJoin('notice_to_proceed', 'notice_to_proceed.upr_id', '=', 'unit_purchase_requests.id');
          $model  =   $model->leftJoin('inspection_acceptance_report', 'inspection_acceptance_report.upr_id', '=', 'unit_purchase_requests.id');
          $model  =   $model->leftJoin('delivery_inspection', 'delivery_inspection.upr_id', '=', 'unit_purchase_requests.id');
          $model  =   $model->leftJoin('vouchers', 'vouchers.upr_id', '=', 'unit_purchase_requests.id');


        if($request != null)
        {

            if($request->has('date_from') != null)
            {
                $model  =   $model->where('unit_purchase_requests.date_prepared', '>=', $request->get('date_from'));
            }

            if($request->has('date_to') != null)
            {
                $model  =   $model->where('unit_purchase_requests.date_prepared', '<=', $request->get('date_to'));
            }

            if($request->get('type') != 'bidding')
            {
                $model      =   $model->where('mode_of_procurement','!=', 'public_bidding');
            }
            else
            {
                $model      =   $model->where('mode_of_procurement','=', 'public_bidding');
            }
        }

        $model  =   $model->whereRaw("YEAR(unit_purchase_requests.date_prepared) <= '$yearto' AND YEAR(unit_purchase_requests.date_prepared) >= '$yearfrom'");

        if($search != null)
        {
            $model  =   $model->where('procurement_centers.name', 'LIKE', "%$search%");
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

        $model  = $model->whereNotNull('procurement_centers.name');

        $model  =   $model->where('unit_purchase_requests.status', '!=', 'draft');

        $model  =   $model->groupBy([
            'procurement_centers.name',
            // 'unit_purchase_requests.id',
            // 'unit_purchase_requests.date_prepared',
            'procurement_centers.id'
        ]);
        return $model->get();
    }


    /**
     * [getDatatable description]
     *
     * @param  [int]    $company_id ['company id ']
     * @return [type]               [description]
     */
    public function getPccoPSR($request = null, $search = null)
    {

        $model  =   $this->model;
        $model  =   $model->select([
            'procurement_centers.name as unit_name',
            DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% ) as philgeps "),
            DB::raw("COUNT(unit_purchase_requests.id) as upr"),
            DB::raw("COUNT(request_for_quotations.id) as rfq"),
            DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
            // DB::raw("COUNT(philgeps_posting.id) as philgeps"),
            DB::raw("COUNT(invitation_for_quotation.id) as ispq"),
            DB::raw("COUNT(canvassing.id) as canvass"),
            DB::raw("COUNT(notice_of_awards.id) as noa"),
            DB::raw("COUNT(notice_of_awards.award_accepted_date) as noaa"),
            DB::raw("COUNT(notice_of_awards.accepted_date) as noar"),
            DB::raw("COUNT(purchase_orders.id) as po"),
            DB::raw("COUNT(purchase_orders.mfo_released_date) as po_mfo_released"),
            DB::raw("COUNT(purchase_orders.mfo_received_date) as po_mfo_received"),
            DB::raw("COUNT(purchase_orders.funding_released_date) as po_pcco_released"),
            DB::raw("COUNT(purchase_orders.funding_received_date) as po_pcco_received"),
            DB::raw("COUNT(purchase_orders.coa_approved_date) as po_coa_approved"),
            DB::raw("COUNT(notice_to_proceed.id) as ntp"),
            DB::raw("COUNT(notice_to_proceed.award_accepted_date) as ntpa"),

            DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as nod "),

            DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as delivery "),

            DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as date_delivered_to_coa "),


            DB::raw("COUNT(inspection_acceptance_report.id) as tiac"),
            DB::raw("COUNT(inspection_acceptance_report.accepted_date) as coa_inspection"),
            DB::raw("COUNT(delivery_inspection.id) as diir"),
            DB::raw("COUNT(delivery_inspection.start_date) as diir_start"),
            DB::raw("COUNT(delivery_inspection.closed_date) as diir_close"),
            DB::raw("COUNT(vouchers.id) as voucher"),
            DB::raw("COUNT(vouchers.id) as end_process"),


            DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as pre_proc "),

            DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as doc "),
            DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as itb "),

            DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as prebid "),

            DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as bidop "),

            DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as pq "),
        ]);
        $model  =   $model->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'unit_purchase_requests.procurement_office');
        $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.upr_id', '=', 'unit_purchase_requests.id');

        // $model  =   $model->leftJoin('philgeps_posting', 'philgeps_posting.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('ispq_quotations', 'ispq_quotations.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('canvassing', 'canvassing.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('notice_of_awards', 'notice_of_awards.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('invitation_for_quotation', 'invitation_for_quotation.id', '=', 'ispq_quotations.ispq_id');
        $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('notice_to_proceed', 'notice_to_proceed.upr_id', '=', 'unit_purchase_requests.id');
        // $model  =   $model->leftJoin('delivery_orders', 'delivery_orders.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('inspection_acceptance_report', 'inspection_acceptance_report.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('delivery_inspection', 'delivery_inspection.upr_id', '=', 'unit_purchase_requests.id');
        // $model  =   $model->leftJoin('rfq_proponents', 'rfq_proponents.rfq_id', '=', 'request_for_quotations.id');
        $model  =   $model->leftJoin('vouchers', 'vouchers.upr_id', '=', 'unit_purchase_requests.id');

        // Biddings
        // $model  =   $model->leftJoin('document_acceptance', 'document_acceptance.upr_id', '=', 'unit_purchase_requests.id');
        // $model  =   $model->leftJoin('invitation_to_bid', 'invitation_to_bid.upr_id', '=', 'unit_purchase_requests.id');
        // $model  =   $model->leftJoin('pre_bid_conferences', 'pre_bid_conferences.upr_id', '=', 'unit_purchase_requests.id');
        // $model  =   $model->leftJoin('bid_opening', 'bid_opening.upr_id', '=', 'unit_purchase_requests.id');
        // $model  =   $model->leftJoin('post_qualification', 'post_qualification.upr_id', '=', 'unit_purchase_requests.id');

        if($request != null)
        {

            if($request->has('date_from') != null)
            {
                $model  =   $model->where('unit_purchase_requests.date_prepared', '>=', $request->get('date_from'));
            }

            if($request->has('date_to') != null)
            {
                $model  =   $model->where('unit_purchase_requests.date_prepared', '<=', $request->get('date_to'));
            }

            if($request->has('type') == null)
            {
                $model      =   $model->where('mode_of_procurement','!=', 'public_bidding');
            }
            else
            {
                $model      =   $model->where('mode_of_procurement','=', 'public_bidding');
            }
        }

        if($search != null)
        {
            $model  =   $model->where('procurement_centers.name', 'LIKE', "%$search%");
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

        $model  = $model->whereNotNull('procurement_centers.name');

        $model  =   $model->where('unit_purchase_requests.status', '!=', 'draft');

        $model  =   $model->groupBy([
            'procurement_centers.name',
            'unit_purchase_requests.id',
            'unit_purchase_requests.date_prepared',
            'procurement_centers.id'
        ]);

        return $model->get();
    }

    /**
     * [getDatatable description]
     *
     * @param  [int]    $company_id ['company id ']
     * @return [type]               [description]
     */
    public function getPSR($request = null, $search = null)
    {
        $model  =   $this->model;
        if($request->has('date_to') != null && $request->has('date_from') != null && $search != null)
        {
           $dateTo = $request->get('date_to');
           $dateFrom = $request->get('date_from');
            if($request != null && $request->has('type') == null)
            {
                $model  =   $model->select([
                    'procurement_centers.name as unit_name',
                    DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search% ) as philgeps "),
                    DB::raw("COUNT(unit_purchase_requests.id) as upr"),
                    DB::raw("COUNT(request_for_quotations.id) as rfq"),
                    DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
                    // DB::raw("COUNT(philgeps_posting.id) as philgeps"),
                    DB::raw("COUNT(invitation_for_quotation.id) as ispq"),
                    DB::raw("COUNT(canvassing.id) as canvass"),
                    DB::raw("COUNT(notice_of_awards.id) as noa"),
                    DB::raw("COUNT(notice_of_awards.award_accepted_date) as noaa"),
                    DB::raw("COUNT(notice_of_awards.accepted_date) as noar"),
                    DB::raw("COUNT(purchase_orders.id) as po"),
                    DB::raw("COUNT(purchase_orders.mfo_released_date) as po_mfo_released"),
                    DB::raw("COUNT(purchase_orders.mfo_received_date) as po_mfo_received"),
                    DB::raw("COUNT(purchase_orders.funding_released_date) as po_pcco_released"),
                    DB::raw("COUNT(purchase_orders.funding_received_date) as po_pcco_received"),
                    DB::raw("COUNT(purchase_orders.coa_approved_date) as po_coa_approved"),
                    DB::raw("COUNT(notice_to_proceed.id) as ntp"),
                    DB::raw("COUNT(notice_to_proceed.award_accepted_date) as ntpa"),

                    DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as nod "),

                    DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as delivery "),

                    DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as date_delivered_to_coa "),


                    // DB::raw("COUNT(delivery_orders.id) as nod"),
                    // DB::raw("COUNT(delivery_orders.delivery_date) as delivery"),
                    DB::raw("COUNT(inspection_acceptance_report.id) as tiac"),
                    DB::raw("COUNT(inspection_acceptance_report.accepted_date) as coa_inspection"),
                    DB::raw("COUNT(delivery_inspection.id) as diir"),
                    DB::raw("COUNT(delivery_inspection.start_date) as diir_start"),
                    DB::raw("COUNT(delivery_inspection.closed_date) as diir_close"),
                    DB::raw("COUNT(vouchers.id) as voucher"),
                    DB::raw("COUNT(vouchers.id) as end_process"),
                    DB::raw("COUNT(vouchers.payment_release_date) as ldad"),
                    // DB::raw("COUNT(document_acceptance.id) as doc"),
                    // DB::raw("COUNT(invitation_to_bid.id) as itb"),


                    DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as pre_proc "),

                    DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as doc "),
                    DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as itb "),

                    DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as prebid "),

                    DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as bidop "),

                    DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as pq "),
                ]);
            }
            else
            {

                $model  =   $model->select([
                    'procurement_centers.name as unit_name',
                    DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as philgeps "),



                    DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as nod "),

                    DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as delivery "),

                    DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as date_delivered_to_coa "),

                    DB::raw("COUNT(unit_purchase_requests.id) as upr"),
                    DB::raw("COUNT(request_for_quotations.id) as rfq"),
                    DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
                    // DB::raw("COUNT(philgeps_posting.id) as philgeps"),
                    DB::raw("COUNT(invitation_for_quotation.id) as ispq"),
                    DB::raw("COUNT(canvassing.id) as canvass"),
                    DB::raw("COUNT(notice_of_awards.id) as noa"),
                    DB::raw("COUNT(notice_of_awards.award_accepted_date) as noaa"),
                    DB::raw("COUNT(notice_of_awards.accepted_date) as noar"),
                    DB::raw("COUNT(purchase_orders.id) as po"),
                    DB::raw("COUNT(purchase_orders.mfo_released_date) as po_mfo_released"),
                    DB::raw("COUNT(purchase_orders.mfo_received_date) as po_mfo_received"),
                    DB::raw("COUNT(purchase_orders.funding_released_date) as po_pcco_released"),
                    DB::raw("COUNT(purchase_orders.funding_received_date) as po_pcco_received"),
                    DB::raw("COUNT(purchase_orders.coa_approved_date) as po_coa_approved"),
                    DB::raw("COUNT(notice_to_proceed.id) as ntp"),
                    DB::raw("COUNT(notice_to_proceed.award_accepted_date) as ntpa"),
                    // DB::raw("COUNT(delivery_orders.id) as nod"),
                    // DB::raw("COUNT(delivery_orders.delivery_date) as delivery"),
                    DB::raw("COUNT(inspection_acceptance_report.id) as tiac"),
                    DB::raw("COUNT(inspection_acceptance_report.accepted_date) as coa_inspection"),
                    DB::raw("COUNT(delivery_inspection.id) as diir"),
                    DB::raw("COUNT(delivery_inspection.start_date) as diir_start"),
                    DB::raw("COUNT(delivery_inspection.closed_date) as diir_close"),
                    DB::raw("COUNT(vouchers.id) as voucher"),
                    DB::raw("COUNT(vouchers.id) as end_process"),
                    // DB::raw("COUNT(document_acceptance.id) as doc"),
                    // DB::raw("COUNT(invitation_to_bid.id) as itb"),

                    DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as itb "),

                    DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as pre_proc "),

                    DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as doc "),

                    DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as prebid "),

                    DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as bidop "),

                    DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom AND unit_purchase_requests.upr_number LIKE %$search%  ) as pq "),
                ]);
            }
        } elseif( $request->has('date_to') != null && $request->has('date_from') != null ){
          $dateTo = $request->get('date_to');
          $dateFrom = $request->get('date_from');
           if($request != null && $request->has('type') == null)
           {
               $model  =   $model->select([
                   'procurement_centers.name as unit_name',
                   DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom   ) as philgeps "),
                   DB::raw("COUNT(unit_purchase_requests.id) as upr"),
                   DB::raw("COUNT(request_for_quotations.id) as rfq"),
                   DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
                   // DB::raw("COUNT(philgeps_posting.id) as philgeps"),
                   DB::raw("COUNT(invitation_for_quotation.id) as ispq"),
                   DB::raw("COUNT(canvassing.id) as canvass"),
                   DB::raw("COUNT(notice_of_awards.id) as noa"),
                   DB::raw("COUNT(notice_of_awards.award_accepted_date) as noaa"),
                   DB::raw("COUNT(notice_of_awards.accepted_date) as noar"),
                   DB::raw("COUNT(purchase_orders.id) as po"),
                   DB::raw("COUNT(purchase_orders.mfo_released_date) as po_mfo_released"),
                   DB::raw("COUNT(purchase_orders.mfo_received_date) as po_mfo_received"),
                   DB::raw("COUNT(purchase_orders.funding_released_date) as po_pcco_released"),
                   DB::raw("COUNT(purchase_orders.funding_received_date) as po_pcco_received"),
                   DB::raw("COUNT(purchase_orders.coa_approved_date) as po_coa_approved"),
                   DB::raw("COUNT(notice_to_proceed.id) as ntp"),
                   DB::raw("COUNT(notice_to_proceed.award_accepted_date) as ntpa"),

                   DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom  ) as nod "),

                   DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom  ) as delivery "),

                   DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom  ) as date_delivered_to_coa "),


                   // DB::raw("COUNT(delivery_orders.id) as nod"),
                   // DB::raw("COUNT(delivery_orders.delivery_date) as delivery"),
                   DB::raw("COUNT(inspection_acceptance_report.id) as tiac"),
                   DB::raw("COUNT(inspection_acceptance_report.accepted_date) as coa_inspection"),
                   DB::raw("COUNT(delivery_inspection.id) as diir"),
                   DB::raw("COUNT(delivery_inspection.start_date) as diir_start"),
                   DB::raw("COUNT(delivery_inspection.closed_date) as diir_close"),
                   DB::raw("COUNT(vouchers.id) as voucher"),
                   DB::raw("COUNT(vouchers.id) as end_process"),
                   // DB::raw("COUNT(document_acceptance.id) as doc"),
                   // DB::raw("COUNT(invitation_to_bid.id) as itb"),


                   DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom  ) as pre_proc "),

                   DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom  ) as doc "),
                   DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom  ) as itb "),

                   DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom  ) as prebid "),

                   DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom  ) as bidop "),

                   DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom  ) as pq "),
               ]);
           }
           else
           {

               $model  =   $model->select([
                   'procurement_centers.name as unit_name',
                   DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom  ) as philgeps "),



                   DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom  ) as nod "),

                   DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom  ) as delivery "),

                   DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom  ) as date_delivered_to_coa "),

                   DB::raw("COUNT(unit_purchase_requests.id) as upr"),
                   DB::raw("COUNT(request_for_quotations.id) as rfq"),
                   DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
                   // DB::raw("COUNT(philgeps_posting.id) as philgeps"),
                   DB::raw("COUNT(invitation_for_quotation.id) as ispq"),
                   DB::raw("COUNT(canvassing.id) as canvass"),
                   DB::raw("COUNT(notice_of_awards.id) as noa"),
                   DB::raw("COUNT(notice_of_awards.award_accepted_date) as noaa"),
                   DB::raw("COUNT(notice_of_awards.accepted_date) as noar"),
                   DB::raw("COUNT(purchase_orders.id) as po"),
                   DB::raw("COUNT(purchase_orders.mfo_released_date) as po_mfo_released"),
                   DB::raw("COUNT(purchase_orders.mfo_received_date) as po_mfo_received"),
                   DB::raw("COUNT(purchase_orders.funding_released_date) as po_pcco_released"),
                   DB::raw("COUNT(purchase_orders.funding_received_date) as po_pcco_received"),
                   DB::raw("COUNT(purchase_orders.coa_approved_date) as po_coa_approved"),
                   DB::raw("COUNT(notice_to_proceed.id) as ntp"),
                   DB::raw("COUNT(notice_to_proceed.award_accepted_date) as ntpa"),
                   // DB::raw("COUNT(delivery_orders.id) as nod"),
                   // DB::raw("COUNT(delivery_orders.delivery_date) as delivery"),
                   DB::raw("COUNT(inspection_acceptance_report.id) as tiac"),
                   DB::raw("COUNT(inspection_acceptance_report.accepted_date) as coa_inspection"),
                   DB::raw("COUNT(delivery_inspection.id) as diir"),
                   DB::raw("COUNT(delivery_inspection.start_date) as diir_start"),
                   DB::raw("COUNT(delivery_inspection.closed_date) as diir_close"),
                   DB::raw("COUNT(vouchers.id) as voucher"),
                   DB::raw("COUNT(vouchers.id) as end_process"),
                   // DB::raw("COUNT(document_acceptance.id) as doc"),
                   // DB::raw("COUNT(invitation_to_bid.id) as itb"),

                   DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom  ) as itb "),

                   DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom  ) as pre_proc "),

                   DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom  ) as doc "),

                   DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id  ) as prebid "),

                   DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom  ) as bidop "),

                   DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.date_prepared >= $dateFrom  ) as pq "),
               ]);
           }
        } elseif( $request->has('date_to') != null && $search != null){

          $dateTo = $request->get('date_to');
          $dateFrom = $request->get('date_from');
           if($request != null && $request->has('type') == null)
           {
              $model  =   $model->select([
                  'procurement_centers.name as unit_name',
                  DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search%  ) as philgeps "),
                  DB::raw("COUNT(unit_purchase_requests.id) as upr"),
                  DB::raw("COUNT(request_for_quotations.id) as rfq"),
                  DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
                  // DB::raw("COUNT(philgeps_posting.id) as philgeps"),
                  DB::raw("COUNT(invitation_for_quotation.id) as ispq"),
                  DB::raw("COUNT(canvassing.id) as canvass"),
                  DB::raw("COUNT(notice_of_awards.id) as noa"),
                  DB::raw("COUNT(notice_of_awards.award_accepted_date) as noaa"),
                  DB::raw("COUNT(notice_of_awards.accepted_date) as noar"),
                  DB::raw("COUNT(purchase_orders.id) as po"),
                  DB::raw("COUNT(purchase_orders.mfo_released_date) as po_mfo_released"),
                  DB::raw("COUNT(purchase_orders.mfo_received_date) as po_mfo_received"),
                  DB::raw("COUNT(purchase_orders.funding_released_date) as po_pcco_released"),
                  DB::raw("COUNT(purchase_orders.funding_received_date) as po_pcco_received"),
                  DB::raw("COUNT(purchase_orders.coa_approved_date) as po_coa_approved"),
                  DB::raw("COUNT(notice_to_proceed.id) as ntp"),
                  DB::raw("COUNT(notice_to_proceed.award_accepted_date) as ntpa"),

                  DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search%  ) as nod "),

                  DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search%  ) as delivery "),

                  DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search%  ) as date_delivered_to_coa "),


                  // DB::raw("COUNT(delivery_orders.id) as nod"),
                  // DB::raw("COUNT(delivery_orders.delivery_date) as delivery"),
                  DB::raw("COUNT(inspection_acceptance_report.id) as tiac"),
                  DB::raw("COUNT(inspection_acceptance_report.accepted_date) as coa_inspection"),
                  DB::raw("COUNT(delivery_inspection.id) as diir"),
                  DB::raw("COUNT(delivery_inspection.start_date) as diir_start"),
                  DB::raw("COUNT(delivery_inspection.closed_date) as diir_close"),
                  DB::raw("COUNT(vouchers.id) as voucher"),
                  DB::raw("COUNT(vouchers.id) as end_process"),
                  // DB::raw("COUNT(document_acceptance.id) as doc"),
                  // DB::raw("COUNT(invitation_to_bid.id) as itb"),


                  DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search%  ) as pre_proc "),

                  DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search%  ) as doc "),
                  DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search%  ) as itb "),

                  DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search%  ) as prebid "),

                  DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search%  ) as bidop "),

                  DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search%  ) as pq "),
              ]);
          }
          else
          {

              $model  =   $model->select([
                  'procurement_centers.name as unit_name',
                  DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search%  ) as philgeps "),



                  DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search%  ) as nod "),

                  DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search%  ) as delivery "),

                  DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search%  ) as date_delivered_to_coa "),

                  DB::raw("COUNT(unit_purchase_requests.id) as upr"),
                  DB::raw("COUNT(request_for_quotations.id) as rfq"),
                  DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
                  // DB::raw("COUNT(philgeps_posting.id) as philgeps"),
                  DB::raw("COUNT(invitation_for_quotation.id) as ispq"),
                  DB::raw("COUNT(canvassing.id) as canvass"),
                  DB::raw("COUNT(notice_of_awards.id) as noa"),
                  DB::raw("COUNT(notice_of_awards.award_accepted_date) as noaa"),
                  DB::raw("COUNT(notice_of_awards.accepted_date) as noar"),
                  DB::raw("COUNT(purchase_orders.id) as po"),
                  DB::raw("COUNT(purchase_orders.mfo_released_date) as po_mfo_released"),
                  DB::raw("COUNT(purchase_orders.mfo_received_date) as po_mfo_received"),
                  DB::raw("COUNT(purchase_orders.funding_released_date) as po_pcco_released"),
                  DB::raw("COUNT(purchase_orders.funding_received_date) as po_pcco_received"),
                  DB::raw("COUNT(purchase_orders.coa_approved_date) as po_coa_approved"),
                  DB::raw("COUNT(notice_to_proceed.id) as ntp"),
                  DB::raw("COUNT(notice_to_proceed.award_accepted_date) as ntpa"),
                  // DB::raw("COUNT(delivery_orders.id) as nod"),
                  // DB::raw("COUNT(delivery_orders.delivery_date) as delivery"),
                  DB::raw("COUNT(inspection_acceptance_report.id) as tiac"),
                  DB::raw("COUNT(inspection_acceptance_report.accepted_date) as coa_inspection"),
                  DB::raw("COUNT(delivery_inspection.id) as diir"),
                  DB::raw("COUNT(delivery_inspection.start_date) as diir_start"),
                  DB::raw("COUNT(delivery_inspection.closed_date) as diir_close"),
                  DB::raw("COUNT(vouchers.id) as voucher"),
                  DB::raw("COUNT(vouchers.id) as end_process"),
                  // DB::raw("COUNT(document_acceptance.id) as doc"),
                  // DB::raw("COUNT(invitation_to_bid.id) as itb"),

                  DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search%  ) as itb "),

                  DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search%  ) as pre_proc "),

                  DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search%  ) as doc "),

                  DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id   AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search% ) as prebid "),

                  DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search%  ) as bidop "),

                  DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo AND unit_purchase_requests.upr_number LIKE %$search%  ) as pq "),
              ]);
          }
        } elseif($request->has('date_from') != null && $search != null){
          $dateTo = $request->get('date_to');
          $dateFrom = $request->get('date_from');
           if($request != null && $request->has('type') == null)
           {
               $model  =   $model->select([
                   'procurement_centers.name as unit_name',
                   DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom ) as philgeps "),
                   DB::raw("COUNT(unit_purchase_requests.id) as upr"),
                   DB::raw("COUNT(request_for_quotations.id) as rfq"),
                   DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
                   // DB::raw("COUNT(philgeps_posting.id) as philgeps"),
                   DB::raw("COUNT(invitation_for_quotation.id) as ispq"),
                   DB::raw("COUNT(canvassing.id) as canvass"),
                   DB::raw("COUNT(notice_of_awards.id) as noa"),
                   DB::raw("COUNT(notice_of_awards.award_accepted_date) as noaa"),
                   DB::raw("COUNT(notice_of_awards.accepted_date) as noar"),
                   DB::raw("COUNT(purchase_orders.id) as po"),
                   DB::raw("COUNT(purchase_orders.mfo_released_date) as po_mfo_released"),
                   DB::raw("COUNT(purchase_orders.mfo_received_date) as po_mfo_received"),
                   DB::raw("COUNT(purchase_orders.funding_released_date) as po_pcco_released"),
                   DB::raw("COUNT(purchase_orders.funding_received_date) as po_pcco_received"),
                   DB::raw("COUNT(purchase_orders.coa_approved_date) as po_coa_approved"),
                   DB::raw("COUNT(notice_to_proceed.id) as ntp"),
                   DB::raw("COUNT(notice_to_proceed.award_accepted_date) as ntpa"),

                   DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom  ) as nod "),

                   DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom  ) as delivery "),

                   DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom  ) as date_delivered_to_coa "),


                   // DB::raw("COUNT(delivery_orders.id) as nod"),
                   // DB::raw("COUNT(delivery_orders.delivery_date) as delivery"),
                   DB::raw("COUNT(inspection_acceptance_report.id) as tiac"),
                   DB::raw("COUNT(inspection_acceptance_report.accepted_date) as coa_inspection"),
                   DB::raw("COUNT(delivery_inspection.id) as diir"),
                   DB::raw("COUNT(delivery_inspection.start_date) as diir_start"),
                   DB::raw("COUNT(delivery_inspection.closed_date) as diir_close"),
                   DB::raw("COUNT(vouchers.id) as voucher"),
                   DB::raw("COUNT(vouchers.id) as end_process"),
                   // DB::raw("COUNT(document_acceptance.id) as doc"),
                   // DB::raw("COUNT(invitation_to_bid.id) as itb"),


                   DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom  ) as pre_proc "),

                   DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom  ) as doc "),
                   DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom  ) as itb "),

                   DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom  ) as prebid "),

                   DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom  ) as bidop "),

                   DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom  ) as pq "),
               ]);
           }
           else
           {

               $model  =   $model->select([
                   'procurement_centers.name as unit_name',
                   DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom  ) as philgeps "),



                   DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom  ) as nod "),

                   DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom  ) as delivery "),

                   DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom  ) as date_delivered_to_coa "),

                   DB::raw("COUNT(unit_purchase_requests.id) as upr"),
                   DB::raw("COUNT(request_for_quotations.id) as rfq"),
                   DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
                   // DB::raw("COUNT(philgeps_posting.id) as philgeps"),
                   DB::raw("COUNT(invitation_for_quotation.id) as ispq"),
                   DB::raw("COUNT(canvassing.id) as canvass"),
                   DB::raw("COUNT(notice_of_awards.id) as noa"),
                   DB::raw("COUNT(notice_of_awards.award_accepted_date) as noaa"),
                   DB::raw("COUNT(notice_of_awards.accepted_date) as noar"),
                   DB::raw("COUNT(purchase_orders.id) as po"),
                   DB::raw("COUNT(purchase_orders.mfo_released_date) as po_mfo_released"),
                   DB::raw("COUNT(purchase_orders.mfo_received_date) as po_mfo_received"),
                   DB::raw("COUNT(purchase_orders.funding_released_date) as po_pcco_released"),
                   DB::raw("COUNT(purchase_orders.funding_received_date) as po_pcco_received"),
                   DB::raw("COUNT(purchase_orders.coa_approved_date) as po_coa_approved"),
                   DB::raw("COUNT(notice_to_proceed.id) as ntp"),
                   DB::raw("COUNT(notice_to_proceed.award_accepted_date) as ntpa"),
                   // DB::raw("COUNT(delivery_orders.id) as nod"),
                   // DB::raw("COUNT(delivery_orders.delivery_date) as delivery"),
                   DB::raw("COUNT(inspection_acceptance_report.id) as tiac"),
                   DB::raw("COUNT(inspection_acceptance_report.accepted_date) as coa_inspection"),
                   DB::raw("COUNT(delivery_inspection.id) as diir"),
                   DB::raw("COUNT(delivery_inspection.start_date) as diir_start"),
                   DB::raw("COUNT(delivery_inspection.closed_date) as diir_close"),
                   DB::raw("COUNT(vouchers.id) as voucher"),
                   DB::raw("COUNT(vouchers.id) as end_process"),
                   // DB::raw("COUNT(document_acceptance.id) as doc"),
                   // DB::raw("COUNT(invitation_to_bid.id) as itb"),

                   DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom  ) as itb "),

                   DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom  ) as pre_proc "),

                   DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom  ) as doc "),

                   DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id  ) as prebid "),

                   DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom  ) as bidop "),

                   DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom  ) as pq "),
               ]);
           }
        } elseif($search != null){
          $dateTo = $request->get('date_to');
          $dateFrom = $request->get('date_from');
           if($request != null && $request->has('type') == null)
           {
               $model  =   $model->select([
                   'procurement_centers.name as unit_name',
                   DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id  AND unit_purchase_requests.upr_number LIKE %$search% ) as philgeps "),
                   DB::raw("COUNT(unit_purchase_requests.id) as upr"),
                   DB::raw("COUNT(request_for_quotations.id) as rfq"),
                   DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
                   // DB::raw("COUNT(philgeps_posting.id) as philgeps"),
                   DB::raw("COUNT(invitation_for_quotation.id) as ispq"),
                   DB::raw("COUNT(canvassing.id) as canvass"),
                   DB::raw("COUNT(notice_of_awards.id) as noa"),
                   DB::raw("COUNT(notice_of_awards.award_accepted_date) as noaa"),
                   DB::raw("COUNT(notice_of_awards.accepted_date) as noar"),
                   DB::raw("COUNT(purchase_orders.id) as po"),
                   DB::raw("COUNT(purchase_orders.mfo_released_date) as po_mfo_released"),
                   DB::raw("COUNT(purchase_orders.mfo_received_date) as po_mfo_received"),
                   DB::raw("COUNT(purchase_orders.funding_released_date) as po_pcco_released"),
                   DB::raw("COUNT(purchase_orders.funding_received_date) as po_pcco_received"),
                   DB::raw("COUNT(purchase_orders.coa_approved_date) as po_coa_approved"),
                   DB::raw("COUNT(notice_to_proceed.id) as ntp"),
                   DB::raw("COUNT(notice_to_proceed.award_accepted_date) as ntpa"),

                   DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id  AND unit_purchase_requests.upr_number LIKE %$search%  ) as nod "),

                   DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id  AND unit_purchase_requests.upr_number LIKE %$search%  ) as delivery "),

                   DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id  AND unit_purchase_requests.upr_number LIKE %$search%  ) as date_delivered_to_coa "),


                   // DB::raw("COUNT(delivery_orders.id) as nod"),
                   // DB::raw("COUNT(delivery_orders.delivery_date) as delivery"),
                   DB::raw("COUNT(inspection_acceptance_report.id) as tiac"),
                   DB::raw("COUNT(inspection_acceptance_report.accepted_date) as coa_inspection"),
                   DB::raw("COUNT(delivery_inspection.id) as diir"),
                   DB::raw("COUNT(delivery_inspection.start_date) as diir_start"),
                   DB::raw("COUNT(delivery_inspection.closed_date) as diir_close"),
                   DB::raw("COUNT(vouchers.id) as voucher"),
                   DB::raw("COUNT(vouchers.id) as end_process"),
                   // DB::raw("COUNT(document_acceptance.id) as doc"),
                   // DB::raw("COUNT(invitation_to_bid.id) as itb"),


                   DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id  AND unit_purchase_requests.upr_number LIKE %$search%  ) as pre_proc "),

                   DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id  AND unit_purchase_requests.upr_number LIKE %$search%  ) as doc "),
                   DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id  AND unit_purchase_requests.upr_number LIKE %$search%  ) as itb "),

                   DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id  AND unit_purchase_requests.upr_number LIKE %$search%  ) as prebid "),

                   DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id  AND unit_purchase_requests.upr_number LIKE %$search%  ) as bidop "),

                   DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id  AND unit_purchase_requests.upr_number LIKE %$search%  ) as pq "),
               ]);
           }
           else
           {

               $model  =   $model->select([
                   'procurement_centers.name as unit_name',
                   DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id  AND unit_purchase_requests.upr_number LIKE %$search%  ) as philgeps "),



                   DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id  AND unit_purchase_requests.upr_number LIKE %$search%  ) as nod "),

                   DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id  AND unit_purchase_requests.upr_number LIKE %$search%  ) as delivery "),

                   DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id  AND unit_purchase_requests.upr_number LIKE %$search%  ) as date_delivered_to_coa "),

                   DB::raw("COUNT(unit_purchase_requests.id) as upr"),
                   DB::raw("COUNT(request_for_quotations.id) as rfq"),
                   DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
                   // DB::raw("COUNT(philgeps_posting.id) as philgeps"),
                   DB::raw("COUNT(invitation_for_quotation.id) as ispq"),
                   DB::raw("COUNT(canvassing.id) as canvass"),
                   DB::raw("COUNT(notice_of_awards.id) as noa"),
                   DB::raw("COUNT(notice_of_awards.award_accepted_date) as noaa"),
                   DB::raw("COUNT(notice_of_awards.accepted_date) as noar"),
                   DB::raw("COUNT(purchase_orders.id) as po"),
                   DB::raw("COUNT(purchase_orders.mfo_released_date) as po_mfo_released"),
                   DB::raw("COUNT(purchase_orders.mfo_received_date) as po_mfo_received"),
                   DB::raw("COUNT(purchase_orders.funding_released_date) as po_pcco_released"),
                   DB::raw("COUNT(purchase_orders.funding_received_date) as po_pcco_received"),
                   DB::raw("COUNT(purchase_orders.coa_approved_date) as po_coa_approved"),
                   DB::raw("COUNT(notice_to_proceed.id) as ntp"),
                   DB::raw("COUNT(notice_to_proceed.award_accepted_date) as ntpa"),
                   // DB::raw("COUNT(delivery_orders.id) as nod"),
                   // DB::raw("COUNT(delivery_orders.delivery_date) as delivery"),
                   DB::raw("COUNT(inspection_acceptance_report.id) as tiac"),
                   DB::raw("COUNT(inspection_acceptance_report.accepted_date) as coa_inspection"),
                   DB::raw("COUNT(delivery_inspection.id) as diir"),
                   DB::raw("COUNT(delivery_inspection.start_date) as diir_start"),
                   DB::raw("COUNT(delivery_inspection.closed_date) as diir_close"),
                   DB::raw("COUNT(vouchers.id) as voucher"),
                   DB::raw("COUNT(vouchers.id) as end_process"),
                   // DB::raw("COUNT(document_acceptance.id) as doc"),
                   // DB::raw("COUNT(invitation_to_bid.id) as itb"),

                   DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id  AND unit_purchase_requests.upr_number LIKE %$search%  ) as itb "),

                   DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id  AND unit_purchase_requests.upr_number LIKE %$search%  ) as pre_proc "),

                   DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id  AND unit_purchase_requests.upr_number LIKE %$search%  ) as doc "),

                   DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id  AND unit_purchase_requests.upr_number LIKE %$search%  ) as prebid "),

                   DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id  AND unit_purchase_requests.upr_number LIKE %$search%  ) as bidop "),

                   DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id  AND unit_purchase_requests.upr_number LIKE %$search%  ) as pq "),
               ]);
           }
        }elseif($request->has('date_to') != null){
          $dateTo = $request->get('date_to');
          $dateFrom = $request->get('date_from');
           if($request != null && $request->has('type') == null)
           {
               $model  =   $model->select([
                   'procurement_centers.name as unit_name',
                   DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo  ) as philgeps "),
                   DB::raw("COUNT(unit_purchase_requests.id) as upr"),
                   DB::raw("COUNT(request_for_quotations.id) as rfq"),
                   DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
                   // DB::raw("COUNT(philgeps_posting.id) as philgeps"),
                   DB::raw("COUNT(invitation_for_quotation.id) as ispq"),
                   DB::raw("COUNT(canvassing.id) as canvass"),
                   DB::raw("COUNT(notice_of_awards.id) as noa"),
                   DB::raw("COUNT(notice_of_awards.award_accepted_date) as noaa"),
                   DB::raw("COUNT(notice_of_awards.accepted_date) as noar"),
                   DB::raw("COUNT(purchase_orders.id) as po"),
                   DB::raw("COUNT(purchase_orders.mfo_released_date) as po_mfo_released"),
                   DB::raw("COUNT(purchase_orders.mfo_received_date) as po_mfo_received"),
                   DB::raw("COUNT(purchase_orders.funding_released_date) as po_pcco_released"),
                   DB::raw("COUNT(purchase_orders.funding_received_date) as po_pcco_received"),
                   DB::raw("COUNT(purchase_orders.coa_approved_date) as po_coa_approved"),
                   DB::raw("COUNT(notice_to_proceed.id) as ntp"),
                   DB::raw("COUNT(notice_to_proceed.award_accepted_date) as ntpa"),

                   DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo   ) as nod "),

                   DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo   ) as delivery "),

                   DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo   ) as date_delivered_to_coa "),


                   // DB::raw("COUNT(delivery_orders.id) as nod"),
                   // DB::raw("COUNT(delivery_orders.delivery_date) as delivery"),
                   DB::raw("COUNT(inspection_acceptance_report.id) as tiac"),
                   DB::raw("COUNT(inspection_acceptance_report.accepted_date) as coa_inspection"),
                   DB::raw("COUNT(delivery_inspection.id) as diir"),
                   DB::raw("COUNT(delivery_inspection.start_date) as diir_start"),
                   DB::raw("COUNT(delivery_inspection.closed_date) as diir_close"),
                   DB::raw("COUNT(vouchers.id) as voucher"),
                   DB::raw("COUNT(vouchers.id) as end_process"),
                   // DB::raw("COUNT(document_acceptance.id) as doc"),
                   // DB::raw("COUNT(invitation_to_bid.id) as itb"),


                   DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo   ) as pre_proc "),

                   DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo   ) as doc "),
                   DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo   ) as itb "),

                   DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo   ) as prebid "),

                   DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo   ) as bidop "),

                   DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo   ) as pq "),
               ]);
           }
           else
           {

               $model  =   $model->select([
                   'procurement_centers.name as unit_name',
                   DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo   ) as philgeps "),



                   DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo   ) as nod "),

                   DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo   ) as delivery "),

                   DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo   ) as date_delivered_to_coa "),

                   DB::raw("COUNT(unit_purchase_requests.id) as upr"),
                   DB::raw("COUNT(request_for_quotations.id) as rfq"),
                   DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
                   // DB::raw("COUNT(philgeps_posting.id) as philgeps"),
                   DB::raw("COUNT(invitation_for_quotation.id) as ispq"),
                   DB::raw("COUNT(canvassing.id) as canvass"),
                   DB::raw("COUNT(notice_of_awards.id) as noa"),
                   DB::raw("COUNT(notice_of_awards.award_accepted_date) as noaa"),
                   DB::raw("COUNT(notice_of_awards.accepted_date) as noar"),
                   DB::raw("COUNT(purchase_orders.id) as po"),
                   DB::raw("COUNT(purchase_orders.mfo_released_date) as po_mfo_released"),
                   DB::raw("COUNT(purchase_orders.mfo_received_date) as po_mfo_received"),
                   DB::raw("COUNT(purchase_orders.funding_released_date) as po_pcco_released"),
                   DB::raw("COUNT(purchase_orders.funding_received_date) as po_pcco_received"),
                   DB::raw("COUNT(purchase_orders.coa_approved_date) as po_coa_approved"),
                   DB::raw("COUNT(notice_to_proceed.id) as ntp"),
                   DB::raw("COUNT(notice_to_proceed.award_accepted_date) as ntpa"),
                   // DB::raw("COUNT(delivery_orders.id) as nod"),
                   // DB::raw("COUNT(delivery_orders.delivery_date) as delivery"),
                   DB::raw("COUNT(inspection_acceptance_report.id) as tiac"),
                   DB::raw("COUNT(inspection_acceptance_report.accepted_date) as coa_inspection"),
                   DB::raw("COUNT(delivery_inspection.id) as diir"),
                   DB::raw("COUNT(delivery_inspection.start_date) as diir_start"),
                   DB::raw("COUNT(delivery_inspection.closed_date) as diir_close"),
                   DB::raw("COUNT(vouchers.id) as voucher"),
                   DB::raw("COUNT(vouchers.id) as end_process"),
                   // DB::raw("COUNT(document_acceptance.id) as doc"),
                   // DB::raw("COUNT(invitation_to_bid.id) as itb"),

                   DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo   ) as itb "),

                   DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo   ) as pre_proc "),

                   DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo   ) as doc "),

                   DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo   ) as prebid "),

                   DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo   ) as bidop "),

                   DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared <= $dateTo   ) as pq "),
               ]);
           }
        } elseif($request->has('date_from') != null){
          $dateTo = $request->get('date_to');
          $dateFrom = $request->get('date_from');
           if($request != null && $request->has('type') == null)
           {
               $model  =   $model->select([
                   'procurement_centers.name as unit_name',
                   DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom  ) as philgeps "),
                   DB::raw("COUNT(unit_purchase_requests.id) as upr"),
                   DB::raw("COUNT(request_for_quotations.id) as rfq"),
                   DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
                   // DB::raw("COUNT(philgeps_posting.id) as philgeps"),
                   DB::raw("COUNT(invitation_for_quotation.id) as ispq"),
                   DB::raw("COUNT(canvassing.id) as canvass"),
                   DB::raw("COUNT(notice_of_awards.id) as noa"),
                   DB::raw("COUNT(notice_of_awards.award_accepted_date) as noaa"),
                   DB::raw("COUNT(notice_of_awards.accepted_date) as noar"),
                   DB::raw("COUNT(purchase_orders.id) as po"),
                   DB::raw("COUNT(purchase_orders.mfo_released_date) as po_mfo_released"),
                   DB::raw("COUNT(purchase_orders.mfo_received_date) as po_mfo_received"),
                   DB::raw("COUNT(purchase_orders.funding_released_date) as po_pcco_released"),
                   DB::raw("COUNT(purchase_orders.funding_received_date) as po_pcco_received"),
                   DB::raw("COUNT(purchase_orders.coa_approved_date) as po_coa_approved"),
                   DB::raw("COUNT(notice_to_proceed.id) as ntp"),
                   DB::raw("COUNT(notice_to_proceed.award_accepted_date) as ntpa"),

                   DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom   ) as nod "),

                   DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom   ) as delivery "),

                   DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom   ) as date_delivered_to_coa "),


                   // DB::raw("COUNT(delivery_orders.id) as nod"),
                   // DB::raw("COUNT(delivery_orders.delivery_date) as delivery"),
                   DB::raw("COUNT(inspection_acceptance_report.id) as tiac"),
                   DB::raw("COUNT(inspection_acceptance_report.accepted_date) as coa_inspection"),
                   DB::raw("COUNT(delivery_inspection.id) as diir"),
                   DB::raw("COUNT(delivery_inspection.start_date) as diir_start"),
                   DB::raw("COUNT(delivery_inspection.closed_date) as diir_close"),
                   DB::raw("COUNT(vouchers.id) as voucher"),
                   DB::raw("COUNT(vouchers.id) as end_process"),
                   // DB::raw("COUNT(document_acceptance.id) as doc"),
                   // DB::raw("COUNT(invitation_to_bid.id) as itb"),


                   DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom   ) as pre_proc "),

                   DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom   ) as doc "),
                   DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom   ) as itb "),

                   DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom   ) as prebid "),

                   DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom   ) as bidop "),

                   DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom   ) as pq "),
               ]);
           }
           else
           {

               $model  =   $model->select([
                   'procurement_centers.name as unit_name',
                   DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom   ) as philgeps "),



                   DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom   ) as nod "),

                   DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom   ) as delivery "),

                   DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom   ) as date_delivered_to_coa "),

                   DB::raw("COUNT(unit_purchase_requests.id) as upr"),
                   DB::raw("COUNT(request_for_quotations.id) as rfq"),
                   DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
                   // DB::raw("COUNT(philgeps_posting.id) as philgeps"),
                   DB::raw("COUNT(invitation_for_quotation.id) as ispq"),
                   DB::raw("COUNT(canvassing.id) as canvass"),
                   DB::raw("COUNT(notice_of_awards.id) as noa"),
                   DB::raw("COUNT(notice_of_awards.award_accepted_date) as noaa"),
                   DB::raw("COUNT(notice_of_awards.accepted_date) as noar"),
                   DB::raw("COUNT(purchase_orders.id) as po"),
                   DB::raw("COUNT(purchase_orders.mfo_released_date) as po_mfo_released"),
                   DB::raw("COUNT(purchase_orders.mfo_received_date) as po_mfo_received"),
                   DB::raw("COUNT(purchase_orders.funding_released_date) as po_pcco_released"),
                   DB::raw("COUNT(purchase_orders.funding_received_date) as po_pcco_received"),
                   DB::raw("COUNT(purchase_orders.coa_approved_date) as po_coa_approved"),
                   DB::raw("COUNT(notice_to_proceed.id) as ntp"),
                   DB::raw("COUNT(notice_to_proceed.award_accepted_date) as ntpa"),
                   // DB::raw("COUNT(delivery_orders.id) as nod"),
                   // DB::raw("COUNT(delivery_orders.delivery_date) as delivery"),
                   DB::raw("COUNT(inspection_acceptance_report.id) as tiac"),
                   DB::raw("COUNT(inspection_acceptance_report.accepted_date) as coa_inspection"),
                   DB::raw("COUNT(delivery_inspection.id) as diir"),
                   DB::raw("COUNT(delivery_inspection.start_date) as diir_start"),
                   DB::raw("COUNT(delivery_inspection.closed_date) as diir_close"),
                   DB::raw("COUNT(vouchers.id) as voucher"),
                   DB::raw("COUNT(vouchers.id) as end_process"),
                   // DB::raw("COUNT(document_acceptance.id) as doc"),
                   // DB::raw("COUNT(invitation_to_bid.id) as itb"),

                   DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom   ) as itb "),

                   DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom   ) as pre_proc "),

                   DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom   ) as doc "),

                   DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom   ) as prebid "),

                   DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom   ) as bidop "),

                   DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id AND unit_purchase_requests.date_prepared >= $dateFrom   ) as pq "),
               ]);
           }
        }else{
          $dateTo = $request->get('date_to');
          $dateFrom = $request->get('date_from');
           if($request != null && $request->has('type') == null)
           {
               $model  =   $model->select([
                   'procurement_centers.name as unit_name',
                   DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id AND philgeps_posting.upr_id = unit_purchase_requests.id  ) as philgeps "),
                   DB::raw("COUNT(unit_purchase_requests.id) as upr"),
                   DB::raw("COUNT(request_for_quotations.id) as rfq"),
                   DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
                   // DB::raw("COUNT(philgeps_posting.id) as philgeps"),
                   DB::raw("COUNT(invitation_for_quotation.id) as ispq"),
                   DB::raw("COUNT(canvassing.id) as canvass"),
                   DB::raw("COUNT(notice_of_awards.id) as noa"),
                   DB::raw("COUNT(notice_of_awards.award_accepted_date) as noaa"),
                   DB::raw("COUNT(notice_of_awards.accepted_date) as noar"),
                   DB::raw("COUNT(purchase_orders.id) as po"),
                   DB::raw("COUNT(purchase_orders.mfo_released_date) as po_mfo_released"),
                   DB::raw("COUNT(purchase_orders.mfo_received_date) as po_mfo_received"),
                   DB::raw("COUNT(purchase_orders.funding_released_date) as po_pcco_released"),
                   DB::raw("COUNT(purchase_orders.funding_received_date) as po_pcco_received"),
                   DB::raw("COUNT(purchase_orders.coa_approved_date) as po_coa_approved"),
                   DB::raw("COUNT(notice_to_proceed.id) as ntp"),
                   DB::raw("COUNT(notice_to_proceed.award_accepted_date) as ntpa"),

                   DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id    ) as nod "),

                   DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id    ) as delivery "),

                   DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id    ) as date_delivered_to_coa "),


                   // DB::raw("COUNT(delivery_orders.id) as nod"),
                   // DB::raw("COUNT(delivery_orders.delivery_date) as delivery"),
                   DB::raw("COUNT(inspection_acceptance_report.id) as tiac"),
                   DB::raw("COUNT(inspection_acceptance_report.accepted_date) as coa_inspection"),
                   DB::raw("COUNT(delivery_inspection.id) as diir"),
                   DB::raw("COUNT(delivery_inspection.start_date) as diir_start"),
                   DB::raw("COUNT(delivery_inspection.closed_date) as diir_close"),
                   DB::raw("COUNT(vouchers.id) as voucher"),
                   DB::raw("COUNT(vouchers.id) as end_process"),
                   // DB::raw("COUNT(document_acceptance.id) as doc"),
                   // DB::raw("COUNT(invitation_to_bid.id) as itb"),


                   DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id    ) as pre_proc "),

                   DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id    ) as doc "),
                   DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id    ) as itb "),

                   DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id    ) as prebid "),

                   DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id    ) as bidop "),

                   DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  != 'public_bidding' and upr.procurement_office = procurement_centers.id    ) as pq "),
               ]);
           }
           else
           {

               $model  =   $model->select([
                   'procurement_centers.name as unit_name',
                   DB::raw(" (select count(philgeps_posting.id) from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id  AND philgeps_posting.upr_id = unit_purchase_requests.id  ) as philgeps "),



                   DB::raw(" (select count(delivery_orders.id) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id    ) as nod "),

                   DB::raw(" (select count(delivery_orders.delivery_date) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id    ) as delivery "),

                   DB::raw(" (select count(delivery_orders.date_delivered_to_coa) from delivery_orders left join unit_purchase_requests as upr on delivery_orders.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id    ) as date_delivered_to_coa "),

                   DB::raw("COUNT(unit_purchase_requests.id) as upr"),
                   DB::raw("COUNT(request_for_quotations.id) as rfq"),
                   DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
                   // DB::raw("COUNT(philgeps_posting.id) as philgeps"),
                   DB::raw("COUNT(invitation_for_quotation.id) as ispq"),
                   DB::raw("COUNT(canvassing.id) as canvass"),
                   DB::raw("COUNT(notice_of_awards.id) as noa"),
                   DB::raw("COUNT(notice_of_awards.award_accepted_date) as noaa"),
                   DB::raw("COUNT(notice_of_awards.accepted_date) as noar"),
                   DB::raw("COUNT(purchase_orders.id) as po"),
                   DB::raw("COUNT(purchase_orders.mfo_released_date) as po_mfo_released"),
                   DB::raw("COUNT(purchase_orders.mfo_received_date) as po_mfo_received"),
                   DB::raw("COUNT(purchase_orders.funding_released_date) as po_pcco_released"),
                   DB::raw("COUNT(purchase_orders.funding_received_date) as po_pcco_received"),
                   DB::raw("COUNT(purchase_orders.coa_approved_date) as po_coa_approved"),
                   DB::raw("COUNT(notice_to_proceed.id) as ntp"),
                   DB::raw("COUNT(notice_to_proceed.award_accepted_date) as ntpa"),
                   // DB::raw("COUNT(delivery_orders.id) as nod"),
                   // DB::raw("COUNT(delivery_orders.delivery_date) as delivery"),
                   DB::raw("COUNT(inspection_acceptance_report.id) as tiac"),
                   DB::raw("COUNT(inspection_acceptance_report.accepted_date) as coa_inspection"),
                   DB::raw("COUNT(delivery_inspection.id) as diir"),
                   DB::raw("COUNT(delivery_inspection.start_date) as diir_start"),
                   DB::raw("COUNT(delivery_inspection.closed_date) as diir_close"),
                   DB::raw("COUNT(vouchers.id) as voucher"),
                   DB::raw("COUNT(vouchers.id) as end_process"),
                   // DB::raw("COUNT(document_acceptance.id) as doc"),
                   // DB::raw("COUNT(invitation_to_bid.id) as itb"),

                   DB::raw(" (select count(invitation_to_bid.id) from invitation_to_bid left join unit_purchase_requests as upr on invitation_to_bid.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id    ) as itb "),

                   DB::raw(" (select count(pre_proc.id) from pre_proc left join unit_purchase_requests as upr on pre_proc.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id    ) as pre_proc "),

                   DB::raw(" (select count(document_acceptance.id) from document_acceptance left join unit_purchase_requests as upr on document_acceptance.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id    ) as doc "),

                   DB::raw(" (select count(pre_bid_conferences.id) from pre_bid_conferences left join unit_purchase_requests as upr on pre_bid_conferences.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id    ) as prebid "),

                   DB::raw(" (select count(bid_opening.id) from bid_opening left join unit_purchase_requests as upr on bid_opening.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id    ) as bidop "),

                   DB::raw(" (select count(post_qualification.id) from post_qualification left join unit_purchase_requests as upr on post_qualification.upr_id  = upr.id where mode_of_procurement  = 'public_bidding' and upr.procurement_office = procurement_centers.id    ) as pq "),
               ]);
           }
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
        // $model  =   $model->leftJoin('delivery_orders', 'delivery_orders.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('inspection_acceptance_report', 'inspection_acceptance_report.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('delivery_inspection', 'delivery_inspection.upr_id', '=', 'unit_purchase_requests.id');
        // $model  =   $model->leftJoin('rfq_proponents', 'rfq_proponents.rfq_id', '=', 'request_for_quotations.id');
        $model  =   $model->leftJoin('vouchers', 'vouchers.upr_id', '=', 'unit_purchase_requests.id');

        // Biddings
        // $model  =   $model->leftJoin('document_acceptance', 'document_acceptance.upr_id', '=', 'unit_purchase_requests.id');
        // $model  =   $model->leftJoin('invitation_to_bid', 'invitation_to_bid.upr_id', '=', 'unit_purchase_requests.id');
        // $model  =   $model->leftJoin('pre_bid_conferences', 'pre_bid_conferences.upr_id', '=', 'unit_purchase_requests.id');
        // $model  =   $model->leftJoin('bid_opening', 'bid_opening.upr_id', '=', 'unit_purchase_requests.id');
        // $model  =   $model->leftJoin('post_qualification', 'post_qualification.upr_id', '=', 'unit_purchase_requests.id');

        if($request != null)
        {

            if($request->has('date_from') != null)
            {
                $model  =   $model->where('unit_purchase_requests.date_prepared', '>=', $request->get('date_from'));
            }

            if($request->has('date_to') != null)
            {
                $model  =   $model->where('unit_purchase_requests.date_prepared', '<=', $request->get('date_to'));
            }

            if($request->has('type') == null)
            {
                $model      =   $model->where('mode_of_procurement','!=', 'public_bidding');
            }
            else
            {
                $model      =   $model->where('mode_of_procurement','=', 'public_bidding');
            }
        }

        if($search != null)
        {
            $model  =   $model->where('procurement_centers.name', 'LIKE', "%$search%");
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

        $model  = $model->whereNotNull('procurement_centers.name');

        $model  =   $model->where('unit_purchase_requests.status', '!=', 'draft');

        $model  =   $model->groupBy([
            'procurement_centers.name',
            'unit_purchase_requests.id',
            'unit_purchase_requests.date_prepared',
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
    public function getPSRUnits($request = null, $search = null)
    {

        $model  =   $this->model;

        $model  =   $model->select([
            'catered_units.short_code as unit_name',
            DB::raw("COUNT(unit_purchase_requests.id) as upr"),
            DB::raw("COUNT(request_for_quotations.id) as rfq"),
            DB::raw("COUNT(request_for_quotations.completed_at) as rfq_close"),
            DB::raw("COUNT(philgeps_posting.id) as philgeps"),
            DB::raw("COUNT(invitation_for_quotation.id) as ispq"),
            DB::raw("COUNT(canvassing.id) as canvass"),
            DB::raw("COUNT(notice_of_awards.id) as noa"),
            DB::raw("COUNT(notice_of_awards.award_accepted_date) as noaa"),
            DB::raw("COUNT(notice_of_awards.accepted_date) as noar"),
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
            DB::raw("COUNT(vouchers.preaudit_date) as end_process"),
            DB::raw("COUNT(vouchers.payment_release_date) as ldad"),
            DB::raw("COUNT(document_acceptance.id) as doc"),
            DB::raw("COUNT(invitation_to_bid.id) as itb"),
            DB::raw("COUNT(pre_bid_conferences.id) as prebid"),
            DB::raw("COUNT(bid_opening.id) as bidop"),
            DB::raw("COUNT(post_qualification.id) as pq"),
            DB::raw("COUNT(pre_proc.id) as pre_proc"),

        ]);

        $model  =   $model->leftJoin('catered_units', 'catered_units.id', '=', 'unit_purchase_requests.units');
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
        $model  =   $model->leftJoin('pre_proc', 'pre_proc.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('document_acceptance', 'document_acceptance.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('invitation_to_bid', 'invitation_to_bid.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('pre_bid_conferences', 'pre_bid_conferences.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('bid_opening', 'bid_opening.upr_id', '=', 'unit_purchase_requests.id');
        $model  =   $model->leftJoin('post_qualification', 'post_qualification.upr_id', '=', 'unit_purchase_requests.id');

        if($request != null)
        {

            if($request->has('type') == null)
            {
                $model      =   $model->where('mode_of_procurement','!=', 'public_bidding');
            }
            else
            {
                $model      =   $model->where('mode_of_procurement','=', 'public_bidding');
            }
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

        $model  = $model->whereNotNull('catered_units.id');

        if($search != null)
        {
            $model  =   $model->where('catered_units.short_code', 'LIKE', "%$search%");
        }

        $model  =   $model->groupBy([
            'catered_units.short_code'
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

    /**
     * [getPSRDatatable description]
     *
     * @return [type] [description]
     */
    public function getUnitPSRDatatable($request)
    {
        $model      =   $this->getPSRUnits($request);
        // dd($model);
        return $this->dataTable($model);
    }

    /**
     * [getPSRDatatable description]
     *
     * @return [type] [description]
     */
    public function getPccoPSRDatatable($request)
    {
        $model      =   $this->getPSRPcco($request);
        // dd($model);
        return $this->dataTable($model);
    }

    /**
     * [getPSRPcco description]
     *
     * @param  [type] $type    [description]
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    public function getPSRPcco($type, $request = null)
    {
        $model    = $this->model;
        $model    = $model->select([
            'procurement_centers.short_code',
            DB::raw('COUNT(unit_purchase_requests.id) as upr_count'),
            DB::raw('SUM(unit_purchase_requests.total_amount) as total_abc'),
        ]);

        $model    = $model->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'unit_purchase_requests.procurement_office');

        if($type == 'bidding')
        {
            $model      =   $model->where('mode_of_procurement','=', 'public_bidding');
        }
        else
        {
            $model      =   $model->where('mode_of_procurement','!=', 'public_bidding');
        }

        $model  = $model->whereNotNull('procurement_centers.short_code');

        $model    = $model->groupBY(['short_code']);

        return $model->get();
    }


    public function getUnitPSR($type, $request = null)
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
            $date_to  =   $dateTo;
            $yearto  =   \Carbon\Carbon::createFromFormat('Y-m-d', $date_to)->format('Y');
        }

        if($dateTo &&  $dateFrom == null)
        {
            $dateFrom  =   $date_to;
            $yearfrom  =   \Carbon\Carbon::createFromFormat('Y-m-d', $dateFrom)->format('Y');
        }

        $model    = $this->model;
        $model    = $model->select([
            'procurement_centers.short_code',
            DB::raw('COUNT(unit_purchase_requests.id) as upr_count'),
            DB::raw('SUM(unit_purchase_requests.total_amount) as total_abc'),
        ]);

        $model    = $model->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'unit_purchase_requests.procurement_office');

        $model  =   $model->whereRaw("YEAR(unit_purchase_requests.date_prepared) <= '$yearto' AND YEAR(unit_purchase_requests.date_prepared) >= '$yearfrom'");

        if($request->has('date_from') != null)
        {
            $model  =   $model->where('unit_purchase_requests.date_prepared', '>=', $request->get('date_from'));
        }

        if($request->has('date_to') != null)
        {
            $model  =   $model->where('unit_purchase_requests.date_prepared', '<=', $request->get('date_to'));
        }

        if($type == 'bidding')
        {
            $model      =   $model->where('mode_of_procurement','=', 'public_bidding');
        }
        else
        {
            $model      =   $model->where('mode_of_procurement','!=', 'public_bidding');
        }

        $model  = $model->whereNotNull('procurement_centers.short_code');

        $model    = $model->groupBY(['short_code']);

        return $model->get();
    }

    /**
     * [getUnitPSRItem description]
     *
     * @param  [type] $type    [description]
     * @param  [type] $unit    [description]
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    public function getUnitPSRItem($type, $unit, $request = null)
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
          $date_to  =   $dateTo;
          $yearto  =   \Carbon\Carbon::createFromFormat('Y-m-d', $date_to)->format('Y');
      }

      if($dateTo &&  $dateFrom == null)
      {
          $dateFrom  =   $date_to;
          $yearfrom  =   \Carbon\Carbon::createFromFormat('Y-m-d', $dateFrom)->format('Y');
      }

      $model    = $this->model;
      $model    = $model->select([
          'unit_purchase_requests.project_name',
          'unit_purchase_requests.upr_number',
          'unit_purchase_requests.total_amount',
          'unit_purchase_requests.date_prepared',
          DB::raw("5 * (DATEDIFF(vouchers.preaudit_date, unit_purchase_requests.date_prepared) DIV 7) + MID('0123444401233334012222340111123400001234000123440', 7 * WEEKDAY(unit_purchase_requests.date_prepared) + WEEKDAY(vouchers.preaudit_date) + 1, 1) as calendar_days"),
          'unit_purchase_requests.date_prepared as upr_created_at',
          'unit_purchase_requests.date_prepared as upr_created_at',

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

          DB::raw(" (select philgeps_posting.days from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id where philgeps_posting.upr_id = unit_purchase_requests.id order by philgeps_posting.created_at desc limit 1) as pp_days "),

          DB::raw(" (select philgeps_posting.philgeps_posting from philgeps_posting left join unit_purchase_requests as upr on philgeps_posting.upr_id  = upr.id  where philgeps_posting.upr_id = unit_purchase_requests.id order by philgeps_posting.created_at desc limit 1) as pp_completed_at "),

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
          // 'vouchers.payment_received_date as ldad',
      ]);

      $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.upr_id', '=', 'unit_purchase_requests.id');
      $model  =   $model->leftJoin('ispq_quotations', 'ispq_quotations.upr_id', '=', 'unit_purchase_requests.id');
      $model  =   $model->leftJoin('invitation_for_quotation', 'invitation_for_quotation.id', '=', 'ispq_quotations.ispq_id');
      $model  =   $model->leftJoin('canvassing', 'canvassing.upr_id', '=', 'unit_purchase_requests.id');
      $model  =   $model->leftJoin('notice_of_awards', 'notice_of_awards.upr_id', '=', 'unit_purchase_requests.id');
      $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.upr_id', '=', 'unit_purchase_requests.id');
      $model  =   $model->leftJoin('notice_to_proceed', 'notice_to_proceed.upr_id', '=', 'unit_purchase_requests.id');
      // $model  =   $model->leftJoin('delivery_orders', 'delivery_orders.upr_id', '=', 'unit_purchase_requests.id');
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

      $model  =   $model->groupBy([
          'unit_purchase_requests.id',
          'unit_purchase_requests.project_name',
          'unit_purchase_requests.upr_number',
          'unit_purchase_requests.mode_of_procurement',
          'unit_purchase_requests.ref_number',
          'unit_purchase_requests.date_prepared',
          'unit_purchase_requests.state',
          'unit_purchase_requests.total_amount',
          'unit_purchase_requests.date_processed',
          // 'unit_purchase_requests.calendar_days',
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
          // 'philgeps_posting.philgeps_posting',
          // 'philgeps_posting.days',
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
          'notice_to_proceed.days',
          'notice_to_proceed.accepted_days',
          'notice_to_proceed.prepared_date',
          'notice_to_proceed.award_accepted_date',
          // 'delivery_orders.transaction_date',
          // 'delivery_orders.delivery_date',
          // 'delivery_orders.date_delivered_to_coa',
          // 'delivery_orders.days',
          // 'delivery_orders.delivery_days',
          // 'delivery_orders.dr_coa_days',
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


      $model  =   $model->whereRaw("YEAR(unit_purchase_requests.date_prepared) <= '$yearto' AND YEAR(unit_purchase_requests.date_prepared) >= '$yearfrom'");

      if($request->has('date_from') != null)
      {
          $model  =   $model->where('unit_purchase_requests.date_prepared', '>=', $request->get('date_from'));
      }

      if($request->has('date_to') != null)
      {
          $model  =   $model->where('unit_purchase_requests.date_prepared', '<=', $request->get('date_to'));
      }

      $model    = $model->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'unit_purchase_requests.procurement_office');

      if($type == 'bidding')
      {
          $model      =   $model->where('mode_of_procurement','=', 'public_bidding');
      }
      else
      {
          $model      =   $model->where('mode_of_procurement','!=', 'public_bidding');
      }

      $model    = $model->where('procurement_centers.short_code', '=', $unit);

      return $model->get();
    }

}