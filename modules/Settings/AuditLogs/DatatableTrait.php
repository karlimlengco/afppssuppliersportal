<?php

namespace Revlv\Settings\AuditLogs;

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
            'audits.id',
            'audits.event',
            'audits.user_id',
            'audits.ip_address',
            'audits.auditable_type',
            'audits.created_at',
            \DB::raw("CONCAT(users.first_name,' ', users.surname) AS fullname"),
        ]);

        $model  =   $model->leftJoin('users', 'users.id', '=', 'audits.user_id');

        $model  =   $model->where('new_values', '<>', "[]");

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
            ->addColumn('event', function ($data) {

                $route  =  route('settings.audit-logs.show', $data->id);
                return ' <a  href="'.$route.'" > '. $data->event .'</a>';
            })
            ->rawColumns(['event'])
            ->make(true);
    }
}