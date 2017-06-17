<?php

namespace Revlv\Procurements\Canvassing;

use Illuminate\Http\Request;
use DB;
use Datatables;

trait NOATrait
{


    /**
     * [getDatatable description]
     *
     * @param  [int]    $company_id ['company id ']
     * @return [type]               [description]
     */
    public function getNOADatatable()
    {
        $model  =   $this->model;

        $model  =   $model->select([
            'canvassing.rfq_id',
            'canvassing.rfq_number as canvass_rfq',
            'canvassing.canvass_date',
            'canvassing.adjourned_time',
            'canvassing.upr_number',
            'canvassing.id as canvass_id',
            'canvassing.created_at',
            'rfq_proponents.proponents',
            'rfq_proponents.is_awarded',
            'rfq_proponents.awarded_date',
            'suppliers.name',
        ]);

        $model  =   $model->leftJoin('rfq_proponents', 'rfq_proponents.rfq_id', '=', 'canvassing.rfq_id');
        $model  =   $model->leftJoin('suppliers', 'suppliers.id', '=', 'rfq_proponents.proponents');

        $model  =   $model->whereNotNull('adjourned_time');
        $model  =   $model->whereNotNull('awarded_date');

        $model->orderBy('created_at', 'desc');

        return $this->dataTable($model->get());
    }
}