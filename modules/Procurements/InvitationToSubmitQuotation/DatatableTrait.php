<?php

namespace Revlv\Procurements\InvitationToSubmitQuotation;

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
        $model  =   $model->select(['invitation_for_quotation.*', 'p.upr_id' ]);
        $model  = $model->leftJoin('ispq_quotations as p', function ($q) {
           $q->on('ispq_id', '=', 'invitation_for_quotation.id')
             ->on('p.created_at', '=',
               DB::raw('(select min(created_at) from ispq_quotations where ispq_id = p.ispq_id)'));
         });
        $model  =   $model->leftJoin('unit_purchase_requests', 'unit_purchase_requests.id', '=', 'p.upr_id');

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
            ->addColumn('transaction_date', function ($data) {
                $route  =  route( 'procurements.ispq.edit',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->transaction_date .'</a>';
            })
            ->editColumn('print_button', function ($data) {
                $route  =  route( 'procurements.ispq.print',[$data->id] );
                return ' <a  href="'.$route.'" tooltip="Print">  <span class="nc-icon-glyph tech_print"></span> </a>';
            })
            ->rawColumns(['transaction_date', 'print_button'])
            ->make(true);
    }
}