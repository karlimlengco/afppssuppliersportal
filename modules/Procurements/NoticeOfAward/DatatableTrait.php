<?php

namespace Revlv\Procurements\NoticeOfAward;

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
    public function getDatatable()
    {
        $model  =   $this->model;

        $model  =   $model->select([
            'notice_of_awards.awarded_date',
            'notice_of_awards.id',
            'request_for_quotations.rfq_number',
            'request_for_quotations.upr_number',
            'suppliers.name'
        ]);

        $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.id', '=', 'notice_of_awards.rfq_id');

        $model  =   $model->leftJoin('rfq_proponents', 'rfq_proponents.id', '=', 'notice_of_awards.proponent_id');

        $model  =   $model->leftJoin('suppliers', 'suppliers.id', '=', 'rfq_proponents.proponents');

        $model->orderBy('notice_of_awards.created_at', 'desc');

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
                $route  =  route( 'procurements.noa.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->rfq_number .'</a>';
            })
            ->rawColumns(['rfq_number'])
            ->make(true);
    }
}