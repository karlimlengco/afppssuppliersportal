<?php

namespace Revlv\Procurements\Canvassing\Signatories;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class SignatoryRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SignatoryEloquent::class;
    }

    /**
     * [deleteAllByCanvass description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteAllByCanvass($id)
    {
        $model  =   $this->model;

        $model  =   $model->where('canvass_id', '=', $id);

        $model  =   $model->delete();

        return $model;
    }

}
