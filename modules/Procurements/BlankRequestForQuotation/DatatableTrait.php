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

        $model  =   $model->select(['request_for_quotations.*']);

        $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.id','=', 'request_for_quotations.upr_id');


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
                $route  =  route( 'procurements.blank-rfq.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->rfq_number .'</a>';
            })
            ->editColumn('print_button', function ($data) {
                $route  =   route('procurements.blank-rfq.print',$data->id);
                return '<a target="_blank" href="'.$route.'" tooltip="Print"> <span class="nc-icon-mini tech_print"></span>  </a>';
            })
            ->editColumn('status', function($data){
                return ucfirst($data->status);
            })
            ->rawColumns(['rfq_number', 'print_button'])
            ->make(true);
    }
}