<?php

namespace Revlv\Procurements\DeliveryOrder;

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

        if($type == 'alternative')
        {
            $model  =   $model->select([
                'delivery_orders.*',
                'request_for_quotations.rfq_number'
            ]);

            $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.id', '=', 'delivery_orders.rfq_id');
            $model  =   $model->whereNotNull('rfq_id');
        }
        else
        {
            $model  =   $model->select([
                'delivery_orders.*',
            ]);
            $model  =   $model->whereNull('rfq_id');

            $user = \Sentinel::getUser();
            if($user->hasRole('BAC Operation') || $user->hasRole('BAC Admin'))
            {
              $model  =   $model->leftJoin('document_acceptance', 'document_acceptance.upr_id','=', 'delivery_orders.upr_id');
              $model  =   $model->leftJoin('bacsec', 'bacsec.id','=', 'document_acceptance.bac_id');
              $center =   0;
              if($user->units)
              {
                  if($user->units->centers)
                  {
                      $center =   $user->units->centers->id;
                  }
              }

              $model  =   $model->Where('bacsec.pcco_id','=', $center);
            }
        }

        $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.id','=', 'delivery_orders.upr_id');


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
                 $query->orWhere('delivery_orders.upr_number', 'like', "%$search%");
                 $query->orWhere('delivery_orders.expected_date', 'like', "%$search%");
                 $query->orWhere('delivery_orders.delivery_number', 'like', "%$search%");
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

        if($type == 'alternative')
        {
            $model  =   $model->select([
                'delivery_orders.*',
                'request_for_quotations.rfq_number'
            ]);

            $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.id', '=', 'delivery_orders.rfq_id');
            $model  =   $model->whereNotNull('rfq_id');
        }
        else
        {
            $model  =   $model->select([
                'delivery_orders.*',
            ]);
            $model  =   $model->whereNull('rfq_id');

            $user = \Sentinel::getUser();
            if($user->hasRole('BAC Operation') || $user->hasRole('BAC Admin'))
            {
              $model  =   $model->leftJoin('document_acceptance', 'document_acceptance.upr_id','=', 'delivery_orders.upr_id');
              $model  =   $model->leftJoin('bacsec', 'bacsec.id','=', 'document_acceptance.bac_id');
              $center =   0;
              if($user->units)
              {
                  if($user->units->centers)
                  {
                      $center =   $user->units->centers->id;
                  }
              }

              $model  =   $model->Where('bacsec.pcco_id','=', $center);
            }
        }

        $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.id','=', 'delivery_orders.upr_id');


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
     * [getListDatatable description]
     *
     * @param  [int]    $company_id ['company id ']
     * @return [type]               [description]
     */
    public function getListDatatable($id, $type = 'alternative')
    {
        $model  =   $this->model;

        $model  =   $model->where('delivery_orders.upr_id','=', $id);

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
            ->addColumn('rfq_number', function ($data) {
                $route  =  route( 'procurements.delivery-orders.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->rfq_number .'</a>';
            })
            ->editColumn('upr_number', function ($data) {
                $route  =  route( 'biddings.delivery-orders.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->upr_number .'</a>';
            })
            ->editColumn('dtc_rfq_number', function ($data) {
                $route  =  route( 'procurements.delivery-to-coa.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->rfq_number .'</a>';
            })
            ->editColumn('inspect_rfq_number', function ($data) {
                $route  =  route( 'procurements.delivered-inspections.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->rfq_number .'</a>';
            })
            ->rawColumns(['rfq_number','dtc_rfq_number', 'inspect_rfq_number','upr_number'])
            ->make(true);
    }
}