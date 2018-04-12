<?php

namespace Revlv\Users\Logs;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class UserLogRepository extends BaseRepository
{
    use DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return UserLogEloquent::class;
    }

    /**
     * [findUnSeedByAdmin description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function findUnSeedByAdmin($id)
    {
        $model  =   $this->model;

        $model  =   $model->where('admin_id', '=', $id);

        $model  =   $model->whereNull('is_viewed');

        return $model->limit(20);
    }


    /**
     * [findByAdmin description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function findByAdmin($id)
    {
        $model  =   $this->model;

        $model  =   $model->select([
            \DB::raw("CONCAT(users.first_name,' ', users.surname) AS full_name"),
            'audits.auditable_id',
            'audits.auditable_type',
            'audits.event',
            'audits.url',
        ]);

        $model  =   $model->leftJoin('audits', 'audits.id', 'user_logs.audit_id');
        $model  =   $model->leftJoin('users', 'users.id', 'user_logs.admin_id');

        $model  =   $model->where('admin_id', '=', $id);
        $model  =   $model->orderBy('audits.created_at', 'desc');
        return $model->paginate(40);
    }


    /**
     * [seenByAdmin description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function seenByAdmin($id)
    {
        $model  =   $this->model;

        $model  =   $model->where('admin_id', '=', $id);

        $model  =   $model->whereNull('is_viewed');

        $results = $model->get();

        foreach($results as $data )
        {
            $data->update(['is_viewed' => 1]);
        }

    }

}
