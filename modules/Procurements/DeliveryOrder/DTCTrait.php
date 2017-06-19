<?php

namespace Revlv\Procurements\DeliveryOrder;

use Illuminate\Http\Request;
use DB;
use Datatables;

trait DTCTrait
{

    /**
     * [getDatatable description]
     *
     * @param  [int]    $company_id ['company id ']
     * @return [type]               [description]
     */
    public function getDTCDatatable()
    {
        $model  =   $this->model;

        $model  =   $model->whereStatus('Accepted');

        $model  =   $model->orderBy('created_at', 'desc');

        return $this->dataTable($model->get());
    }
}