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
            ->addColumn('public_upr_number', function ($data) {
                $route  =  route( 'biddings.unit-purchase-requests.show',[$data->id] );
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
                $days = $data->rfq_days;

                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })
            ->editColumn('d_close_blank_rfq', function($data){
                $days = $data->rfq_close_days;
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
                $days = $data->pp_days;

                if($days > 3)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })
            ->editColumn('d_canvass', function($data){
                $days = $data->canvass_days;
                if($days > 2)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })
            ->editColumn('d_noa', function($data){
                $days = $data->noa_days;

                if($days > 2)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_noa_approved', function($data){
                $days = $data->noa_approved_days;
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_noa_accepted', function($data){
                $days = $data->noa_received_days;
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_po_create_date', function($data){
                $days = $data->po_days;
                if($days > 2)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_fund_po_create_date', function($data){
                $days = $data->po_funding_days;
                if($days > 2)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_mfo_released_date', function($data){

                $days = $data->po_mfo_days;
                if($days > 2)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_coa_approved_date', function($data){
                $days = $data->po_coa_days;
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_ntp_date', function($data){

                $days = $data->ntp_days;
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_ntp_award_date', function($data){

                $days = $data->ntp_accepted_days;
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_delivery_date', function($data){
                $days = $data->dr_days;
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_receive_delivery_date', function($data){
                $days = $data->dr_delivery_days;
                if($days > 15)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_dr_coa_date', function($data){
                $days = $data->dr_dr_coa_days;
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_dr_inspection', function($data){
                $days = $data->dr_inspection_days;
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_iar_accepted_date', function($data){
                $days = $data->dr_inspection_accept_days;

                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_di_start', function($data){
                $days = $data->di_days;
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_di_close', function($data){
                $days = $data->di_close_days;
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_vou_start', function($data){
                $days = $data->vou_days;
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })


            ->editColumn('d_preaudit_date', function($data){
                $days = $data->vou_preaudit_days;
                if($days > 2)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_certify_date', function($data){
                $days = $data->vou_certify_days;
                if($days > 2)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_journal_entry_date', function($data){
                $days = $data->vou_jev_days;
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_vou_approval_date', function($data){
                $days =$data->vou_approved_days;
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })


            ->editColumn('d_vou_release', function($data){
                $days = $data->vou_released_days;
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })

            ->editColumn('d_vou_received', function($data){
                $days = $data->vou_received_days;
                if($days > 1)
                {
                    return "<span style='color:red'>".$days."</span>";
                }
                return $days;
            })


            ->rawColumns(['upr_number', 'public_upr_number', 'd_blank_rfq', 'd_vou_received', 'd_vou_release', 'd_vou_approval_date', 'd_journal_entry_date', 'd_certify_date', 'd_preaudit_date', 'd_vou_start', 'd_di_close', 'd_di_start', 'd_iar_accepted_date', 'd_dr_inspection', 'd_dr_coa_date', 'd_receive_delivery_date', 'd_delivery_date' , 'd_ntp_award_date', 'd_ntp_date', 'd_coa_approved_date', 'd_mfo_released_date', 'd_fund_po_create_date', 'd_po_create_date', 'd_noa_accepted', 'd_noa_approved', 'd_noa', 'd_canvass', 'd_philgeps', 'd_ispq', 'd_close_blank_rfq'])
            ->make(true);
    }
}