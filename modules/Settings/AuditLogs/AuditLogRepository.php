<?php

namespace Revlv\Settings\AuditLogs;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class AuditLogRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AuditLogEloquent::class;
    }

    /**
     * [findByModelAndId description]
     *
     * @param  [type] $model [description]
     * @param  [type] $id    [description]
     * @return [type]        [description]
     */
    public function findByModelAndId($modelType, $id)
    {
        $model  =   $this->model;
        $model  =   $model->where('auditable_type', '=', $modelType);

        $model  =   $model->where('auditable_id', '=', $id);

        $model  =   $model->get();

        return $model;
    }

    public function findLastByModelAndId($modelType, $id)
    {
        $model  =   $this->model;
        $model  =   $model->where('auditable_type', '=', $modelType);

        $model  =   $model->where('auditable_id', '=', $id);

        $model  =   $model->orderBy('created_at', 'desc');
        $model  =   $model->first();

        return $model;
    }

}
