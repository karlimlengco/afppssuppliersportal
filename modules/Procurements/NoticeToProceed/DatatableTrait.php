<?php

namespace Revlv\Procurements\NoticeToProceed;

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
                'notice_to_proceed.prepared_date',
                'notice_to_proceed.id',
                'notice_to_proceed.rfq_id',
                'notice_to_proceed.upr_number',
                'purchase_orders.po_number',
                'purchase_orders.purchase_date',
                'purchase_orders.mfo_released_date',
                'purchase_orders.funding_released_date',
                'purchase_orders.bid_amount',
                'purchase_orders.status',
                // 'suppliers.name'
            ]);

            $model  =   $model->leftJoin('rfq_proponents', 'rfq_proponents.id', '=', 'notice_to_proceed.proponent_id');
            $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.id', '=', 'notice_to_proceed.po_id');

            // $model  =   $model->leftJoin('suppliers', 'suppliers.id', '=', 'rfq_proponents.proponents');
            $model  =   $model->whereNotNull('rfq_proponents.id');
        }
        else{

            $model  =   $model->select([
                'notice_to_proceed.prepared_date',
                'notice_to_proceed.id',
                'notice_to_proceed.rfq_id',
                'notice_to_proceed.upr_number',
                'notice_to_proceed.rfq_number',
                'purchase_orders.po_number',
                'purchase_orders.purchase_date',
                'purchase_orders.mfo_released_date',
                'purchase_orders.funding_released_date',
                'purchase_orders.bid_amount',
                'purchase_orders.status',
                // 'suppliers.name'
            ]);

            $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.id', '=', 'notice_to_proceed.po_id');

            // $model  =   $model->leftJoin('bid_docs_issuance', 'bid_docs_issuance.id', '=', 'notice_to_proceed.proponent_id');

            // $model  =   $model->leftJoin('suppliers', 'suppliers.id', '=', 'bid_docs_issuance.proponent_id');

            $model  =   $model->whereNull('notice_to_proceed.rfq_id');

            $user = \Sentinel::getUser();
            if($user->hasRole('BAC Operation') || $user->hasRole('BAC Admin'))
            {
              $model  =   $model->leftJoin('document_acceptance', 'document_acceptance.upr_id','=', 'notice_to_proceed.upr_id');
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

        $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.id','=', 'notice_to_proceed.upr_id');


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
                 $query->orwhere('notice_to_proceed.rfq_number', 'like', "%$search%");
                 $query->orWhere('purchase_orders.po_number', 'like', "%$search%");
                 $query->orWhere('purchase_orders.upr_number', 'like', "%$search%");
                 $query->orWhere('purchase_orders.bid_amount', 'like', "%$search%");
                //  $query->orWhere('suppliers.name', 'like', "%$search%");
                 $query->orWhere('purchase_orders.purchase_date', 'like', "%$search%");
             });
        }
        $model->orderBy('notice_to_proceed.created_at', 'desc');

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
                'notice_to_proceed.prepared_date',
                'notice_to_proceed.id',
                'notice_to_proceed.rfq_id',
                'request_for_quotations.rfq_number',
                'notice_to_proceed.upr_number',
                'purchase_orders.po_number',
                'purchase_orders.purchase_date',
                'purchase_orders.mfo_released_date',
                'purchase_orders.funding_released_date',
                'purchase_orders.bid_amount',
                'purchase_orders.status',
                'suppliers.name'
            ]);

            $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.id', '=', 'notice_to_proceed.rfq_id');

            $model  =   $model->leftJoin('rfq_proponents', 'rfq_proponents.id', '=', 'notice_to_proceed.proponent_id');
            $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.id', '=', 'notice_to_proceed.po_id');

            $model  =   $model->leftJoin('suppliers', 'suppliers.id', '=', 'rfq_proponents.proponents');

            $model  =   $model->whereNotNull('request_for_quotations.id');
        }
        else{

            $model  =   $model->select([
                'notice_to_proceed.prepared_date',
                'notice_to_proceed.id',
                'notice_to_proceed.rfq_id',
                'notice_to_proceed.upr_number',
                'notice_to_proceed.rfq_number',
                'purchase_orders.po_number',
                'purchase_orders.purchase_date',
                'purchase_orders.mfo_released_date',
                'purchase_orders.funding_released_date',
                'purchase_orders.bid_amount',
                'purchase_orders.status',
                'suppliers.name'
            ]);

            $model  =   $model->leftJoin('purchase_orders', 'purchase_orders.id', '=', 'notice_to_proceed.po_id');

            $model  =   $model->leftJoin('bid_docs_issuance', 'bid_docs_issuance.id', '=', 'notice_to_proceed.proponent_id');

            $model  =   $model->leftJoin('suppliers', 'suppliers.id', '=', 'bid_docs_issuance.proponent_id');

            $model  =   $model->whereNull('notice_to_proceed.rfq_id');

            $user = \Sentinel::getUser();
            if($user->hasRole('BAC Operation') || $user->hasRole('BAC Admin'))
            {
              $model  =   $model->leftJoin('document_acceptance', 'document_acceptance.upr_id','=', 'notice_to_proceed.upr_id');
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

        $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.id','=', 'notice_to_proceed.upr_id');


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

        $model->orderBy('notice_to_proceed.created_at', 'desc');

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
                $route  =  route( 'procurements.ntp.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->rfq_number .'</a>';
            })
            ->addColumn('upr_number', function ($data) {
                $route  =  route( 'biddings.ntp.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->upr_number .'</a>';
            })
            ->editColumn('bid_amount', function ($data) {
                return formatPrice($data->bid_amount  );
            })
            ->rawColumns(['rfq_number','upr_number'])
            ->make(true);
    }
}