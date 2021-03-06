<?php

namespace Revlv\Procurements\NoticeOfAward;

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


        $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.id','=', 'notice_of_awards.upr_id');

        if($type == 'alternative')
        {
            $model  =   $model->select([
                'notice_of_awards.awarded_date',
                'notice_of_awards.id',
                'request_for_quotations.rfq_number',
                'notice_of_awards.upr_number',
                // 'suppliers.name'
            ]);

            $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.id', '=', 'notice_of_awards.rfq_id');

            // $model  =   $model->leftJoin('rfq_proponents', 'rfq_proponents.id', '=', 'notice_of_awards.proponent_id');

            // $model  =   $model->leftJoin('suppliers', 'suppliers.id', '=', 'rfq_proponents.proponents');

            // $model  =   $model->whereNotNull('request_for_quotations.rfq_number');
            $model  =   $model->where('unit_purchase_requests.mode_of_procurement', '<>', 'public_bidding');
        }
        else
        {
            $model  =   $model->select([
                'notice_of_awards.awarded_date',
                'notice_of_awards.id',
                'notice_of_awards.upr_number',
                // 'suppliers.name'
            ]);

            // $model  =   $model->leftJoin('bid_docs_issuance', 'bid_docs_issuance.id', '=', 'notice_of_awards.proponent_id');

            // $model  =   $model->leftJoin('suppliers', 'suppliers.id', '=', 'bid_docs_issuance.proponent_id');

            $model  =   $model->where('unit_purchase_requests.mode_of_procurement', '==', 'public_bidding');
            // $model  =   $model->whereNull('rfq_number');

            $user = \Sentinel::getUser();
            if($user->hasRole('BAC Operation') || $user->hasRole('BAC Admin'))
            {
              $model  =   $model->leftJoin('document_acceptance', 'document_acceptance.upr_id','=', 'notice_of_awards.upr_id');
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
                 $query->where('notice_of_awards.upr_number', 'like', "%$search%");
                 $query->orWhere('notice_of_awards.rfq_number', 'like', "%$search%");
                 $query->orWhere('notice_of_awards.awarded_date', 'like', "%$search%");
             });
        }

        $model->orderBy('notice_of_awards.created_at', 'desc');

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
                'notice_of_awards.awarded_date',
                'notice_of_awards.id',
                'request_for_quotations.rfq_number',
                'notice_of_awards.upr_number',
                'suppliers.name'
            ]);

            $model  =   $model->leftJoin('request_for_quotations', 'request_for_quotations.id', '=', 'notice_of_awards.rfq_id');

            $model  =   $model->leftJoin('rfq_proponents', 'rfq_proponents.id', '=', 'notice_of_awards.proponent_id');

            $model  =   $model->leftJoin('suppliers', 'suppliers.id', '=', 'rfq_proponents.proponents');

            $model  =   $model->whereNotNull('request_for_quotations.rfq_number');
        }
        else
        {
            $model  =   $model->select([
                'notice_of_awards.awarded_date',
                'notice_of_awards.id',
                'notice_of_awards.upr_number',
                'suppliers.name'
            ]);

            $model  =   $model->leftJoin('bid_docs_issuance', 'bid_docs_issuance.id', '=', 'notice_of_awards.proponent_id');

            $model  =   $model->leftJoin('suppliers', 'suppliers.id', '=', 'bid_docs_issuance.proponent_id');

            $model  =   $model->whereNull('rfq_number');

            $user = \Sentinel::getUser();
            if($user->hasRole('BAC Operation') || $user->hasRole('BAC Admin'))
            {
              $model  =   $model->leftJoin('document_acceptance', 'document_acceptance.upr_id','=', 'notice_of_awards.upr_id');
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

        $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.id','=', 'notice_of_awards.upr_id');


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

        $model->orderBy('notice_of_awards.created_at', 'desc');

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
                $route  =  route( 'procurements.noa.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->rfq_number .'</a>';
            })
            ->addColumn('bid_upr_number', function ($data) {
                $route  =  route( 'biddings.noa.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->upr_number .'</a>';
            })
            ->rawColumns(['rfq_number', 'bid_upr_number'])
            ->make(true);
    }
}