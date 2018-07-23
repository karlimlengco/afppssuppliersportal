<?php

namespace Revlv\Procurements\Vouchers;

use Illuminate\Http\Request;
use DB;
use Datatables;

trait DatatableTrait
{
    /**
     * [paginateByRequest description]
     *
     * @param  integer $limit   [description]
     * @param  [type]  $request [description]
     * @return [type]           [description]
     */
    public function paginateByRequest($limit = 10, $request, $type = 'alternative')
    {
        $model  =   $this->model;

        $model  =   $model->select([
            'vouchers.*',
            'unit_purchase_requests.id as upr_id',
            'unit_purchase_requests.mode_of_procurement'
        ]);

        $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.id', '=', 'vouchers.upr_id');
        $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.upr_id', '=', 'vouchers.upr_id');
        $model  =   $model->leftJoin('mode_of_procurements', 'mode_of_procurements.id', '=', 'unit_purchase_requests.mode_of_procurement');

        if($type == 'alternative')
        {
            $model  =   $model->whereNotNull('mode_of_procurements.name');
        }
        else
        {
            $model  =   $model->whereNull('mode_of_procurements.name');

            $user = \Sentinel::getUser();
            if($user->hasRole('BAC Operation') || $user->hasRole('BAC Admin'))
            {
              $model  =   $model->leftJoin('document_acceptance', 'document_acceptance.upr_id','=', 'vouchers.upr_id');
              $model  =   $model->leftJoin('bacsec', 'bacsec.id','=', 'document_acceptance.bac_id');
              $center =   0;
              if($user->units)
              {
                  if($user->units->centers)
                  {
                      $center =   $user->units->centers->id;
                  }
              }

              $model  =   $model->where('bacsec.pcco_id','=', $center);
            }
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

        if($request != null)
        {
            $search = $request->search;
            $model  = $model->where(function($query) use ($search){
                 $query->where('request_for_quotations.rfq_number', 'like', "%$search%");
                 $query->where('vouchers.upr_number', 'like', "%$search%");
                 $query->orWhere('vouchers.transaction_date', 'like', "%$search%");
             });
        }
        $model->orderBy('created_at', 'desc');

        return $model->paginate($limit);
    }

    /**
     * [getDatatable description]
     *
     * @param  [int]    $company_id ['company id ']
     * @return [type]               [description]
     */
    public function getDatatable($type = 'alternative')
    {
        $model  =   $this->model;

        $model  =   $model->select([
            'vouchers.*',
            'unit_purchase_requests.id as upr_id',
            'unit_purchase_requests.mode_of_procurement'
        ]);

        $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.id', '=', 'vouchers.upr_id');
        $model  =   $model->leftJoin('mode_of_procurements', 'mode_of_procurements.id', '=', 'unit_purchase_requests.mode_of_procurement');

        if($type == 'alternative')
        {
            $model  =   $model->whereNotNull('mode_of_procurements.name');
        }
        else
        {
            $model  =   $model->whereNull('mode_of_procurements.name');

            $user = \Sentinel::getUser();
            if($user->hasRole('BAC Operation') || $user->hasRole('BAC Admin'))
            {
              $model  =   $model->leftJoin('document_acceptance', 'document_acceptance.upr_id','=', 'vouchers.upr_id');
              $model  =   $model->leftJoin('bacsec', 'bacsec.id','=', 'document_acceptance.bac_id');
              $center =   0;
              if($user->units)
              {
                  if($user->units->centers)
                  {
                      $center =   $user->units->centers->id;
                  }
              }

              $model  =   $model->where('bacsec.pcco_id','=', $center);
            }
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

        $model  =   $model->orderBy('created_at', 'desc');

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
                $route  =  route( 'procurements.vouchers.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->upr_number .'</a>';
            })
            ->rawColumns(['upr_number'])
            ->make(true);
    }
}