<?php

namespace Revlv\Procurements\InspectionAndAcceptance;

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
                'inspection_acceptance_report.*',
                'request_for_quotations.rfq_number'
            ]);

            $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.id', '=', 'inspection_acceptance_report.rfq_id');
            $model  =   $model->whereNotNull('rfq_id');
        }
        else
        {
            $model  =   $model->whereNull('rfq_id');
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
            ->addColumn('delivery_number', function ($data) {
                $route  =  route( 'procurements.inspection-and-acceptance.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->delivery_number .'</a>';
            })
            ->editColumn('bid_delivery_number', function ($data) {
                $route  =  route( 'biddings.inspection-and-acceptance.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->delivery_number .'</a>';
            })
            ->rawColumns(['delivery_number','bid_delivery_number'])
            ->make(true);
    }
}