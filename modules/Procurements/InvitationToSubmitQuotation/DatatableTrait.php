<?php

namespace Revlv\Procurements\InvitationToSubmitQuotation;

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

        $model->orderBy('created_at', 'desc');

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
            ->addColumn('transaction_date', function ($data) {
                $route  =  route( 'procurements.ispq.edit',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->transaction_date .'</a>';
            })
            ->editColumn('print_button', function ($data) {
                $route  =  route( 'procurements.ispq.print',[$data->id] );
                return ' <a  href="'.$route.'" tooltip="Print">  <span class="nc-icon-glyph tech_print"></span> </a>';
            })
            ->rawColumns(['transaction_date', 'print_button'])
            ->make(true);
    }
}