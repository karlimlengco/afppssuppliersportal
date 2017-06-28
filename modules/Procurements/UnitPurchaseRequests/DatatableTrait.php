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
    public function getDatatable()
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
            'unit_purchase_requests.state',
            'mode_of_procurements.name as type',
            DB::raw("CONCAT(users.first_name,' ', users.surname) AS full_name"),
            DB::raw("COUNT(unit_purchase_request_items.id) as item_count")
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
            'unit_purchase_requests.status',
            'unit_purchase_requests.state',
            'unit_purchase_requests.created_at',
            'users.first_name',
            'users.surname',
            'mode_of_procurements.name',
        ]);
        $model  =   $model->orderBy('created_at', 'desc');

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
                return ucfirst($data->status);
            })
            ->editColumn('state', function($data){
                return ucfirst($data->state);
            })
            ->editColumn('d_blank_rfq', function($data){
                $days = 0;
                if($data->rfq_completed_at)
                {
                    $dt         = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->rfq_completed_at);
                    $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->date_prepared);
                    $days       = $dt->diffInDays($upr_create);
                }
                return $days;
            })
            ->editColumn('d_philgeps', function($data){
                $days = 0;
                if($data->pp_completed_at)
                {
                    $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->pp_completed_at);
                    $upr_create = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->rfq_completed_at);
                    $days       = $dt->diffInDays($upr_create);
                }
                return $days;
            })
            ->editColumn('d_ispq', function($data){
                $days = 0;
                if($data->ispq_transaction_date)
                {
                    $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->ispq_transaction_date);
                    $upr_create = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->rfq_completed_at);
                    $days       = $dt->diffInDays($upr_create);
                }
                return $days;
            })
            ->editColumn('d_canvass', function($data){
                $days = 0;
                if($data->canvass_start_date)
                {
                    $dt         = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->canvass_start_date);
                    $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->ispq_transaction_date);
                    $days       = $dt->diffInDays($upr_create);
                }
                return $days;
            })
            ->editColumn('d_noa', function($data){
                $days = 0;
                if($data->noa_award_date)
                {
                    $dt         = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->noa_award_date);
                    $upr_create = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->canvass_start_date);
                    $days       = $dt->diffInDays($upr_create);
                }
                return $days;
            })

            ->editColumn('d_noa_approved', function($data){
                $days = 0;
                if($data->noa_approved_date)
                {
                    $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->noa_approved_date);
                    $upr_create = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->noa_award_date);
                    $days       = $dt->diffInDays($upr_create);
                }
                return $days;
            })

            ->editColumn('d_noa_accepted', function($data){
                $days = 0;
                if($data->noa_award_accepted_date)
                {
                    $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->noa_award_accepted_date);
                    $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->noa_approved_date);
                    $days       = $dt->diffInDays($upr_create);
                }
                return $days;
            })

            ->editColumn('d_po_create_date', function($data){
                $days = 0;
                if($data->po_create_date)
                {
                    $dt         = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->po_create_date);
                    $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->noa_award_accepted_date);
                    $days       = $dt->diffInDays($upr_create);
                }
                return $days;
            })

            ->editColumn('d_mfo_released_date', function($data){
                $days = 0;
                if($data->mfo_released_date)
                {
                    $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->mfo_released_date);
                    $upr_create = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->po_create_date);
                    $days       = $dt->diffInDays($upr_create);
                }
                return $days;
            })

            ->editColumn('d_pcco_released_date', function($data){
                $days = 0;
                if($data->pcco_released_date)
                {
                    $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->pcco_released_date);
                    $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->mfo_released_date);
                    $days       = $dt->diffInDays($upr_create);
                }
                return $days;
            })

            ->editColumn('d_coa_approved_date', function($data){
                $days = 0;
                if($data->coa_approved_date)
                {
                    $dt         = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->coa_approved_date);
                    $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->pcco_released_date);
                    $days       = $dt->diffInDays($upr_create);
                }
                return $days;
            })

            ->editColumn('d_ntp_date', function($data){
                $days = 0;
                if($data->ntp_date)
                {
                    $dt         = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->ntp_date);
                    $upr_create = Carbon\Carbon::createFromFormat('Y-m-d  H:i:s', $data->coa_approved_date);
                    $days       = $dt->diffInDays($upr_create);
                }
                return $days;
            })

            ->editColumn('d_ntp_award_date', function($data){
                $days = 0;
                if($data->ntp_award_date)
                {
                    $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->ntp_award_date);
                    $upr_create = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->ntp_date);
                    $days       = $dt->diffInDays($upr_create);
                }
                return $days;
            })

            ->editColumn('d_delivery_date', function($data){
                $days = 0;
                if($data->delivery_date)
                {
                    $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->delivery_date);
                    $upr_create = Carbon\Carbon::createFromFormat('Y-m-d  H:i:s', $data->dr_date);
                    $days       = $dt->diffInDays($upr_create);
                }
                return $days;
            })

            ->editColumn('d_dr_coa_date', function($data){
                $days = 0;
                if($data->dr_coa_date)
                {
                    $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_coa_date);
                    $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->delivery_date);
                    $days       = $dt->diffInDays($upr_create);
                }
                return $days;
            })

            ->editColumn('d_dr_inspection', function($data){
                $days = 0;
                if($data->dr_inspection)
                {
                    $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_inspection);
                    $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_coa_date);
                    $days       = $dt->diffInDays($upr_create);
                }
                return $days;
            })

            ->editColumn('d_di_start', function($data){
                $days = 0;
                if($data->di_start)
                {
                    $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->di_start);
                    $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->dr_inspection);
                    $days       = $dt->diffInDays($upr_create);
                }
                return $days;
            })

            ->editColumn('d_di_close', function($data){
                $days = 0;
                if($data->di_close)
                {
                    $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->di_close);
                    $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->di_start);
                    $days       = $dt->diffInDays($upr_create);
                }
                return $days;
            })

            ->editColumn('d_vou_start', function($data){
                $days = 0;
                if($data->vou_start)
                {
                    $dt         = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->vou_start);
                    $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->di_close);
                    $days       = $dt->diffInDays($upr_create);
                }
                return $days;
            })

            ->editColumn('d_vou_release', function($data){
                $days = 0;
                if($data->vou_release)
                {
                    $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->vou_release);
                    $upr_create = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->vou_start);
                    $days       = $dt->diffInDays($upr_create);
                }
                return $days;
            })

            ->editColumn('d_vou_received', function($data){
                $days = 0;
                if($data->vou_received)
                {
                    $dt         = Carbon\Carbon::createFromFormat('Y-m-d', $data->vou_received);
                    $upr_create = Carbon\Carbon::createFromFormat('Y-m-d', $data->vou_release);
                    $days       = $dt->diffInDays($upr_create);
                }
                return $days;
            })


            ->rawColumns(['upr_number'])
            ->make(true);
    }
}