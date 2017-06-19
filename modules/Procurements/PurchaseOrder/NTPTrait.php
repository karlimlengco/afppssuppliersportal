<?php

namespace Revlv\Procurements\PurchaseOrder;

use Illuminate\Http\Request;
use DB;
use Datatables;

trait NTPTrait
{

    /**
     * [getDatatable description]
     *
     * @param  [int]    $company_id ['company id ']
     * @return [type]               [description]
     */
    public function getNTPDatatable()
    {
        $model  =   $this->model;

        $model  =   $model->whereNotNull('pcco_released_date');
        $model  =   $model->whereNotNull('mfo_released_date');

        $model  =   $model->orderBy('created_at', 'desc');

        return $this->dataTable($model->get());
    }
}