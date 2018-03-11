<?php

namespace Revlv\Users\Logs;

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

         $model  =   $model->select([
             \DB::raw("CONCAT(users.first_name,' ', users.surname) AS full_name"),
             'audits.id',
             'audits.auditable_id',
             'audits.auditable_type',
             'audits.event',
             'audits.url',
         ]);

         $model  =   $model->leftJoin('audits', 'audits.id', 'user_logs.audit_id');
         $model  =   $model->leftJoin('users', 'users.id', 'user_logs.admin_id');

         $model  =   $model->where('admin_id', '=', \Sentinel::getUser()->id);

         $model  =   $model->orderBy('audits.created_at', 'desc');
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
            ->addColumn('url', function ($data) {
                $id = $data->auditable_id;
                $route  = preg_replace("/$id$/", '', $data->url ). "logs/".$data->auditable_id;
                return ' <a  href="'.$route.'" > View here </a>';
            })
            ->editColumn('auditable_type', function ($data) {
                return $data->auditable_type;
            })
            ->editColumn('full_name', function ($data) {
                return $data->full_name;
            })
            ->rawColumns(['url'])
            ->make(true);
    }
}