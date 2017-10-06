<?php

namespace Revlv\Procurements\PhilGepsPosting;

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
    public function getDatatable($type = null)
    {
        $model  =   $this->model;

        $model  =   $model->select([
            'philgeps_posting.*',
            'unit_purchase_requests.mode_of_procurement'
        ]);

        if($type != null)
        {
            $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.id',  '=', 'philgeps_posting.upr_id');

            $model  =   $model->where('mode_of_procurement', '=', 'public_bidding');
        }
        else
        {

            $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.id',  '=', 'philgeps_posting.upr_id');

            $model  =   $model->where('mode_of_procurement', '<>', 'public_bidding');
        }

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

        $model->orderBy('philgeps_posting.created_at', 'desc');

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
            ->addColumn('philgeps_number', function ($data) {
                $route  =  route( 'procurements.philgeps-posting.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->philgeps_number .'</a>';
            })
            ->addColumn('bid_philgeps_number', function ($data) {
                $route  =  route( 'biddings.philgeps.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->philgeps_number .'</a>';
            })
            ->rawColumns(['philgeps_number'])
            ->make(true);
    }
}