<?php

namespace Revlv\Procurements\PurchaseOrder;

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
                'purchase_orders.*',
                'request_for_quotations.rfq_number'
            ]);

            $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.id', '=', 'purchase_orders.rfq_id');
            $model  =   $model->whereNotNull('rfq_id');
        }
        else
        {
            $model  =   $model->select([
                'purchase_orders.*',
            ]);
            $model  =   $model->whereNull('rfq_id');

            $user = \Sentinel::getUser();
            if($user->hasRole('BAC Operation') || $user->hasRole('BAC Admin'))
            {
              $model  =   $model->leftJoin('document_acceptance', 'document_acceptance.upr_id','=', 'purchase_orders.upr_id');
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

        $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.id','=', 'purchase_orders.upr_id');


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
                 $query->where('purchase_orders.upr_number', 'like', "%$search%");
                 $query->orWhere('purchase_orders.rfq_number', 'like', "%$search%");
                 $query->orWhere('purchase_orders.bid_amount', 'like', "%$search%");
                 $query->orWhere('purchase_orders.purchase_date', 'like', "%$search%");
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
                'purchase_orders.*',
                'request_for_quotations.rfq_number'
            ]);

            $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.id', '=', 'purchase_orders.rfq_id');
            $model  =   $model->whereNotNull('rfq_id');
        }
        else
        {
            $model  =   $model->select([
                'purchase_orders.*',
            ]);
            $model  =   $model->whereNull('rfq_id');

            $user = \Sentinel::getUser();
            if($user->hasRole('BAC Operation') || $user->hasRole('BAC Admin'))
            {
              $model  =   $model->leftJoin('document_acceptance', 'document_acceptance.upr_id','=', 'purchase_orders.upr_id');
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

        $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.id','=', 'purchase_orders.upr_id');


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
            ->addColumn('rfq_number', function ($data) {
                $route  =  route( 'procurements.purchase-orders.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->rfq_number .'</a>';
            })
            ->addColumn('po_number', function ($data) {
                $route  =  route( 'biddings.purchase-orders.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->po_number .'</a>';
            })
            ->editColumn('ntp_rfq_number', function ($data) {
                $route  =  route( 'procurements.ntp.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->rfq_number .'</a>';
            })
            ->editColumn('bid_amount', function ($data) {
                return formatPrice($data->bid_amount);
            })
            ->rawColumns(['rfq_number','ntp_rfq_number', 'po_number'])
            ->make(true);
    }
}