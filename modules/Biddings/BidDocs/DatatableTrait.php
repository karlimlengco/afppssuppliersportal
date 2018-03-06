<?php

namespace Revlv\Biddings\BidDocs;

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
        $model  =   $model->select(['bid_docs_issuance.*', 'suppliers.name as proponent']);
        $model  =   $model->leftJoin('suppliers', 'suppliers.id', '=', 'bid_docs_issuance.proponent_id');
        $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.id','=', 'bid_docs_issuance.upr_id');



        $user = \Sentinel::getUser();
        if(!\Sentinel::getUser()->hasRole('Admin') )
        {

            $center =   0;
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
            ->addColumn('upr_number', function ($data) {
                $route  =  route( 'biddings.unit-purchase-requests.show',[$data->upr_id] );
                return ' <a  href="'.$route.'" > '. $data->upr_number .'</a>';
                // return   $data->upr_number  ;
            })
            ->editColumn('status', function($data){
                return ucfirst($data->status);
            })
            ->rawColumns(['upr_number'])
            ->make(true);
    }
}