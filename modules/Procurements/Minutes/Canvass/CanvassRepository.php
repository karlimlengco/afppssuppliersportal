<?php

namespace Revlv\Procurements\Minutes\Canvass;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class CanvassRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return CanvassEloquent::class;
    }

    /**
     * [deleteAll description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteAll($id)
    {
        $model  =   $this->model;

        $model  =   $model->where('meeting_id', '=', $id);

        $model  =   $model->delete();

        return $model;
    }

    /**
     * [findByRFQId description]
     *
     * @param  [type] $rfq [description]
     * @return [type]      [description]
     */
    public function getById($id)
    {
        $model  =    $this->model;

        $model  =   $model->where('id', '=', $id);

        return $model->first();
    }
}
