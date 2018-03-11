<?php

namespace Revlv\Settings\Suppliers;

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
    public function getDatatable($status = 'accepted')
    {
        $model  =   $this->model;

        $model  =   $model->whereStatus($status);

        $model->orderBy('created_at', 'desc');

        return $this->dataTable($model->get());
    }

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
            'suppliers.name',
            'suppliers.owner',
            'suppliers.is_blocked',
            'supplier_attachments.id',
            'supplier_attachments.type',
            'supplier_attachments.validity_date',
            'supplier_attachments.created_at',
        ]);
        $model  =   $model->leftJoin('supplier_attachments', 'supplier_attachments.supplier_id', 'suppliers.id');
        $model  = $model->whereNotNull('supplier_attachments.id');
        $model->orderBy('suppliers.created_at', 'desc');

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
            ->addColumn('name', function ($data) {
                $route  =  route( 'settings.suppliers.show',[$data->id] );
                return ' <a  href="'.$route.'" > '. $data->name .'</a>';
            })
            ->addColumn('name2', function ($data) {
                return $data->name;
            })
            ->editColumn('is_blocked', function ($data) {
                return ($data->is_blocked == 1) ? "Blocked" : "";
            })
            ->editColumn('view_here', function ($data) {
                $route= route('settings.suppliers.attachments.download', $data->id);
                return ' <a  href="'.$route.'" > Download </a>';
            })
            ->rawColumns(['name', 'view_here'])
            ->make(true);
    }
}