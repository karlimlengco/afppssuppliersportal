<?php

namespace Revlv\Procurements\UnitPurchaseRequests;

use Illuminate\Http\Request;
use DB;
use Datatables;
use Carbon;

trait DatatableTrait
{


    /**
     * [getDatatable description]
     *
     * @param  [int]    $company_id ['company id ']
     * @return [type]               [description]
     */
    public function getDatatable($id = null, $mode = null)
    {
        $model  =   $this->model;

        $model  =   $model->select([
            'unit_purchase_requests.id',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.ref_number',
            'unit_purchase_requests.date_prepared',
            'unit_purchase_requests.created_at',
            'unit_purchase_requests.total_amount',
            'unit_purchase_requests.status',
            'unit_purchase_requests.prepared_by',
            'unit_purchase_requests.project_name',
            'unit_purchase_requests.state',
            // 'mode_of_procurements.name as type',
            DB::raw("CONCAT(users.first_name,' ', users.surname) AS full_name"),
            DB::raw("CASE WHEN mode_of_procurements.name IS NULL THEN 'Public Bidding' ELSE mode_of_procurements.name END as type"),
            DB::raw("COUNT(unit_purchase_request_items.id) as item_count"),
            DB::raw("datediff(NOW(), unit_purchase_requests.date_prepared ) as calendar_days")
        ]);

        $model  =   $model->leftJoin('users', 'users.id', '=', 'unit_purchase_requests.prepared_by');
        $model  =   $model->leftJoin('mode_of_procurements', 'mode_of_procurements.id', '=', 'unit_purchase_requests.mode_of_procurement');
        $model  =   $model->leftJoin('unit_purchase_request_items', 'unit_purchase_request_items.upr_id', '=', 'unit_purchase_requests.id');

        $model  =   $model->groupBy([
            'unit_purchase_requests.id',
            'unit_purchase_requests.upr_number',
            'unit_purchase_requests.date_prepared',
            'unit_purchase_requests.ref_number',
            'unit_purchase_requests.total_amount',
            'unit_purchase_requests.project_name',
            'unit_purchase_requests.prepared_by',
            'unit_purchase_requests.status',
            'unit_purchase_requests.state',
            'unit_purchase_requests.created_at',
            'users.first_name',
            'users.surname',
            'mode_of_procurements.name',
        ]);
        $model  =   $model->orderBy('created_at', 'desc');

        if($id != null)
        {
            $model  =   $model->where('unit_purchase_requests.prepared_by','=', $id);
        }

        if($mode != null)
        {
            $model  =   $model->whereNull('mode_of_procurements.name');
        }
        else
        {
            $model  =   $model->whereNotNull('mode_of_procurements.name');
        }

        return $this->dataTable($model->get());
    }

    /**
     * [dataTable description]
     *
     * @param  [type] $model [description]
     * @return [type]        [description]
     */
    public function dataTable($model)
    {
        return Datatables::of($model)
            ->addColumn('upr_number', function ($data) {
                $route  =  route( 'procurements.unit-purchase-requests.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->upr_number .'</a>';
            })
            ->editColumn('total_amount', function($data){
                return formatPrice($data->total_amount);
            })
            ->editColumn('status', function($data){
                return ucfirst(substr($data->status, 0, 25) );
            })
            ->editColumn('state', function($data){
                return ucfirst($data->state);
            })
            ->editColumn('d_blank_rfq', function($data){
                $days = 0;
                if($data->rfq_created_at != null)
                {
                    $upr_created    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->date_prepared);
                    $rfq_created_at     =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->rfq_created_at);
                    $days               = $rfq_created_at->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $upr_created );
                }
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })
            ->editColumn('d_close_blank_rfq', function($data){
                $days = 0;
                if($data->rfq_completed_at != null)
                {
                    $rfq_completed_ats = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->rfq_completed_at)->format('Y-m-d');
                    $rfq_created_at     =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->rfq_created_at);
                    $rfq_completed_at    =   \Carbon\Carbon::createFromFormat('Y-m-d', $rfq_completed_ats);

                    if($rfq_completed_ats != null)
                    {

                            $days   = $rfq_completed_at->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $rfq_created_at );
                    }
                }
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })
            ->editColumn('d_ispq', function($data){
                $days = 0;
                if($data->ispq_transaction_date != null)
                {
                    $rfq_completed_ats = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->rfq_completed_at)->format('Y-m-d');
                    $rfq_completed_at    =   \Carbon\Carbon::createFromFormat('Y-m-d', $rfq_completed_ats);

                    $ispq_transaction_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->ispq_transaction_date);

                    $days = $ispq_transaction_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $rfq_completed_at );
                }
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })
            ->editColumn('d_philgeps', function($data){
                $days = 0;

                if($data->pp_completed_at != null)
                {
                    $ispq_transaction_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->ispq_transaction_date);
                    $pp_completed_at    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->pp_completed_at);

                    $days = $pp_completed_at->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $ispq_transaction_date );
                }
                if($days > 3)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })
            ->editColumn('d_canvass', function($data){
                $days = 0;
                if($data->canvass_start_date != null && $data->pp_completed_at != null)
                {
                    $canvass_start_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->canvass_start_date);
                    $pp_completed_at    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->pp_completed_at);

                    $days = $canvass_start_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $pp_completed_at );
                }
                if($days > 2)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })
            ->editColumn('d_noa', function($data){
                $days = 0;
                if($data->noa_award_date != null)
                {
                    $noa_award_dates    = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->noa_award_date)->format('Y-m-d');
                    $noa_award_date     =   \Carbon\Carbon::createFromFormat('Y-m-d', $noa_award_dates);
                    $canvass_start_date =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->canvass_start_date);

                    $days = $noa_award_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $canvass_start_date );
                }
                if($days > 2)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_noa_approved', function($data){
                $days = 0;
                if($data->noa_approved_date != null)
                {
                    $noa_approved_date  =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->noa_approved_date);
                    $noa_award_dates    = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->noa_award_date)->format('Y-m-d');
                    $noa_award_date     =   \Carbon\Carbon::createFromFormat('Y-m-d', $noa_award_dates);


                   $days = $noa_approved_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $noa_award_date );
                }
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_noa_accepted', function($data){
                $days = 0;
                if($data->noa_award_accepted_date != null)
                {
                    $noa_award_accepted_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->noa_award_accepted_date);
                    $noa_approved_date  =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->noa_approved_date);

                    $days = $noa_award_accepted_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $noa_approved_date );
                }
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_po_create_date', function($data){
                $days = 0;
                if($data->po_create_date != null)
                {
                    $po_create_date             =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->po_create_date);
                    $noa_award_accepted_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->noa_award_accepted_date);

                    $days = $po_create_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $noa_award_accepted_date );
                }
                if($days > 2)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_fund_po_create_date', function($data){
                $days = 0;
                if($data->funding_received_date != null)
                {
                    $funding_received_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->funding_received_date);
                    $po_create_date             =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->po_create_date);

                    $days = $funding_received_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $po_create_date );
                }
                if($days > 2)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_mfo_released_date', function($data){
                $days = 0;
                if($data->mfo_received_date != null)
                {
                    $mfo_received_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->mfo_received_date);
                    $funding_received_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->funding_received_date);

                    $days = $mfo_received_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $funding_received_date );
                }
                if($days > 2)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_coa_approved_date', function($data){
                $days = 0;
                if($data->coa_approved_date != null)
                {
                    $coa_approved_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->coa_approved_date);
                    $mfo_received_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->mfo_received_date);

                    $days = $coa_approved_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $mfo_received_date );
                }
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_ntp_date', function($data){
                $days = 0;
                if($data->ntp_date != null)
                {
                    $ntp_dates  = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->ntp_date)->format('Y-m-d');
                    $ntp_date   =   \Carbon\Carbon::createFromFormat('Y-m-d', $ntp_dates);

                    $coa_approved_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->coa_approved_date);

                    $days = $ntp_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $coa_approved_date );
                }
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_ntp_award_date', function($data){
                $days = 0;
                if($data->ntp_award_date != null)
                {
                    $ntp_award_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->ntp_award_date);

                    $ntp_dates  = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->ntp_date)->format('Y-m-d');
                    $ntp_date   =   \Carbon\Carbon::createFromFormat('Y-m-d', $ntp_dates);


                    $days = $ntp_award_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $ntp_date );
                }
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_delivery_date', function($data){
                $days = 0;
                if($data->dr_date != null)
                {
                    $dr_date            =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_date);
                    $ntp_award_date     =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->ntp_award_date);

                    $days = $dr_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $ntp_award_date );
                }
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_receive_delivery_date', 'd_delivery_date', function($data){
                $days = 0;
                if($data->delivery_date != null)
                {
                    $delivery_date      =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->delivery_date);
                    $dr_date            =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_date);

                    $days = $delivery_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $dr_date );
                }
                if($days > 15)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_dr_coa_date', function($data){
                $days = 0;
                if($data->dr_coa_date != null)
                {
                    $dr_coa_date        =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_coa_date);
                    $delivery_date      =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->delivery_date);

                    $days = $dr_coa_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $delivery_date );

                }
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_dr_inspection', function($data){
                $days = 0;
                if($data->dr_inspection != null)
                {
                    $dr_inspection      =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_inspection);
                    $dr_coa_date        =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_coa_date);

                    $days = $dr_inspection->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $dr_coa_date );
                }
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_iar_accepted_date', function($data){
                $days = 0;
                if($data->iar_accepted_date != null)
                {
                    $dr_inspection      =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_inspection);
                    $dr_coa_date        =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_coa_date);

                    $days = $dr_inspection->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $dr_coa_date );
                }
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_di_start', function($data){
                $days = 0;
                if($data->di_start != null)
                {
                    $di_start           =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->di_start);
                    $dr_inspection      =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_inspection);

                    $days = $di_start->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $dr_inspection );
                }
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_di_close', function($data){
                $days = 0;
                if($data->di_close != null)
                {
                    $di_close           =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->di_close);
                    $di_start           =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->di_start);

                    $days = $di_close->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $di_start );
                }
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_vou_start', 'd_di_close', function($data){
                $days = 0;
                if($data->v_transaction_date != null)
                {
                    $v_transaction_date =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->v_transaction_date);
                    $di_close           =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->di_close);

                    $days = $v_transaction_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $di_close );
                }
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })


            ->editColumn('d_preaudit_date', function($data){
                $days = 0;
                if($data->preaudit_date != null)
                {
                    $v_transaction_date =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->v_transaction_date);
                    $preaudit_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->preaudit_date);

                    $days = $preaudit_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $v_transaction_date );
                }
                if($days > 2)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_certify_date', function($data){
                $days = 0;
                if($data->certify_date != null)
                {
                    $preaudit_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->preaudit_date);
                    $certify_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->certify_date);

                    $days = $certify_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $preaudit_date );
                }
                if($days > 2)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_journal_entry_date', function($data){
                $days = 0;
                if($data->journal_entry_date != null)
                {
                    $journal_entry_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->journal_entry_date);
                    $certify_date           =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->certify_date);

                    $days = $journal_entry_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $certify_date );
                }
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_vou_approval_date', function($data){
                $days = 0;
                if($data->vou_approval_date != null)
                {
                    $journal_entry_date    =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->journal_entry_date);
                    $vou_approval_date      =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->vou_approval_date);

                    $days = $vou_approval_date->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $journal_entry_date );
                }
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })


            ->editColumn('d_vou_release', function($data){
                $days = 0;
                if($data->vou_release != null)
                {
                    $vou_release            =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->vou_release);
                    $vou_approval_date      =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->vou_approval_date);

                    $days = $vou_release->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $vou_approval_date );
                }
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_vou_received', function($data){
                $days = 0;
                if($data->vou_received != null)
                {
                    $vou_received           =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->vou_received);
                    $vou_release            =   \Carbon\Carbon::createFromFormat('Y-m-d', $data->vou_release);

                    $days = $vou_received->diffInDaysFiltered(function (\Carbon\Carbon $date) {return $date->isWeekday(); }, $vou_release );
                }
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })


            ->rawColumns(['upr_number', 'd_blank_rfq', 'd_vou_received', 'd_vou_release', 'd_vou_approval_date', 'd_journal_entry_date', 'd_certify_date', 'd_preaudit_date', 'd_vou_start', 'd_di_close', 'd_di_start', 'd_iar_accepted_date', 'd_dr_inspection', 'd_dr_coa_date', 'd_receive_delivery_date', 'd_delivery_date' , 'd_ntp_award_date', 'd_ntp_date', 'd_coa_approved_date', 'd_mfo_released_date', 'd_fund_po_create_date', 'd_po_create_date', 'd_noa_accepted', 'd_noa_approved', 'd_noa', 'd_canvass', 'd_philgeps', 'd_ispq', 'd_close_blank_rfq'])
            ->make(true);
    }
}