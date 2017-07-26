<?php

namespace Revlv\Procurements\Vouchers;

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

        $model  =   $model->select([
            'vouchers.*',
            'unit_purchase_requests.id as upr_id',
            'unit_purchase_requests.mode_of_procurement'
        ]);

        $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.id', '=', 'vouchers.upr_id');
        $model  =   $model->leftJoin('mode_of_procurements', 'mode_of_procurements.id', '=', 'unit_purchase_requests.mode_of_procurement');

        if($type == 'alternative')
        {
            $model  =   $model->whereNotNull('mode_of_procurements.name');
        }
        else
        {
            $model  =   $model->whereNull('mode_of_procurements.name');
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
            ->addColumn('upr_number', function ($data) {
                $route  =  route( 'procurements.vouchers.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->upr_number .'</a>';
            })
            ->rawColumns(['upr_number'])
            ->make(true);
    }
}