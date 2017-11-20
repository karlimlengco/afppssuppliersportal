<?php

namespace Revlv\Procurements\Canvassing;

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

        $model  =   $model->select(['canvassing.*']);

        $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.id','=', 'canvassing.upr_id');


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
                $route  =  route( 'procurements.canvassing.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->rfq_number .'</a>';
            })
            ->editColumn('canvass_rfq', function($data){
                $route  =  route( 'procurements.noa.show',[$data->canvass_id] );
                return ' <a  href="'.$route.'" > '. ucfirst($data->canvass_rfq) .'</a>';
            })
            ->editColumn('is_failed', function($data){
                if($data->is_failed == 1)
                {
                    return "Failed";
                }
                return "Passed";
            })
            ->rawColumns(['rfq_number', 'canvass_rfq'])
            ->make(true);
    }
}