<?php

namespace Revlv\Settings\CateredUnits;

use Illuminate\Http\Request;
use DB;
use Datatables;

trait DatatableTrait
{


    /**
     * [getFileDatatable description]
     *
     * @param  [int]    $company_id ['company id ']
     * @return [type]               [description]
     */
    public function getFileDatatable()
    {
        $model  =   $this->model;
        $model  =   $model->select([
            'catered_units.short_code',
            'unit_attachments.id',
            'unit_attachments.validity_date',
            'unit_attachments.amount',
            'unit_attachments.created_at',
        ]);
        $model  =   $model->leftJoin('unit_attachments', 'unit_attachments.unit_id', 'catered_units.id');
        $model  = $model->whereNotNull('unit_attachments.id');
        $model->orderBy('catered_units.created_at', 'desc');

        return $this->dataTable($model->get());
    }

    /**
     * [getDatatable description]
     *
     * @param  [int]    $company_id ['company id ']
     * @return [type]               [description]
     */
    public function getDatatable()
    {
        $model  =   $this->model;
        $model  =   $model->select([
            'catered_units.id',
            'catered_units.*',
            'procurement_centers.name as procurement_name'
        ]);

        $model  =   $model->leftJoin('procurement_centers', 'procurement_centers.id', '=', 'catered_units.pcco_id');

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
            ->addColumn('short_code', function ($data) {
                $route  =  route( 'maintenance.catered-units.edit',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->short_code .'</a>';
            })
            ->editColumn('view_here', function ($data) {
                $route= route('maintenance.catered-units.attachments.download', $data->id);
                return ' <a  href="'.$route.'" > Download </a>';
            })
            ->rawColumns(['short_code', 'view_here'])
            ->make(true);
    }
}