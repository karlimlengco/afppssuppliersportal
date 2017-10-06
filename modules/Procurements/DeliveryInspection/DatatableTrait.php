<?php

namespace Revlv\Procurements\DeliveryInspection;

use Illuminate\Http\Request;
use DB;
use Datatables;

trait DatatableTrait
{

    /**
     * [getDatatable description]
     *
     * @param  [int]    $company_id ['company id ']
     * @return [type]               [description]
     */
    public function getDatatable($type = 'alternative')
    {
        $model  =   $this->model;

        if($type == 'alternative')
        {
            $model  =   $model->select([
                'delivery_inspection.*',
                'request_for_quotations.rfq_number'
            ]);

            $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.id', '=', 'delivery_inspection.rfq_id');
            $model  =   $model->whereNotNull('rfq_id');
        }
        else
        {
            $model  =   $model->select(['delivery_inspection.*']);
            $model  =   $model->whereNull('rfq_id');
        }


        $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.id','=', 'delivery_inspection.upr_id');


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
            ->addColumn('rfq_number', function ($data) {
                $route  =  route( 'procurements.delivered-inspections.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->rfq_number .'</a>';
            })
            ->editColumn('upr_number', function ($data) {
                $route  =  route( 'biddings.delivered-inspections.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->upr_number .'</a>';
            })
            ->rawColumns(['rfq_number','dtc_rfq_number', 'inspect_rfq_number','upr_number'])
            ->make(true);
    }
}