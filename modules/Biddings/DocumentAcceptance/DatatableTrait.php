<?php

namespace Revlv\Biddings\DocumentAcceptance;

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

        $model  =   $model->select(['document_acceptance.*']);

        $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.id','=', 'document_acceptance.upr_id');
        $model  =   $model->leftJoin('bacsec', 'bacsec.id','=', 'document_acceptance.bac_id');


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

        if($user->hasRole('BAC Operation') || $user->hasRole('BAC Admin'))
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

          $model  =   $model->orWhere('bacsec.pcco_id','=', $center);
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
                $route  =  route( 'biddings.document-acceptance.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->upr_number .'</a>';
            })
            ->editColumn('status', function($data){
                return ucfirst($data->status);
            })
            ->rawColumns(['upr_number'])
            ->make(true);
    }
}