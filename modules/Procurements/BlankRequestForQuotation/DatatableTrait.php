<?php

namespace Revlv\Procurements\BlankRequestForQuotation;

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
            ->addColumn('rfq_number', function ($data) {
                $route  =  route( 'procurements.blank-rfq.edit',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->rfq_number .'</a>';
            })
            ->editColumn('print_button', function ($data) {
                return '<a href="#" tooltip="Print"> <span class="nc-icon-mini tech_print"></span>  </a>';
            })
            ->editColumn('status', function($data){
                return ucfirst($data->status);
            })
            ->rawColumns(['rfq_number', 'print_button'])
            ->make(true);
    }
}