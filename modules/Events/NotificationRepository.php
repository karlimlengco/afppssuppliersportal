<?php

namespace Revlv\Events;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class NotificationRepository extends BaseRepository
{
    use DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return NotificationEloquent::class;
    }

    /**
     * [getById description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getByUser($id)
    {
        $model  =   $this->model;

        $model  =   $model->where('user_id','=', $id);

        $model  =   $model->get();

        return $model;
    }

    /**
     *
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function markAsSeen($id)
    {

        $model  =   $this->model;

        $model  =   $model->where('user_id','=', $id);

        $model  =   $model->whereNULL('is_seen');

        $model  =   $model->update(['is_seen' => 1]);

        return true;
    }

    /**
     * [getById description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getUnseenByUser($id)
    {
        $model  =   $this->model;

        $model  =   $model->where('user_id','=', $id);
        $model  =   $model->whereNULL('is_seen');

        $model  =   $model->get();
        return $model;
    }

}
