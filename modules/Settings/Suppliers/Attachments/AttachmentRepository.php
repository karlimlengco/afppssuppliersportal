<?php

namespace Revlv\Settings\Suppliers\Attachments;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class AttachmentRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AttachmentEloquent::class;
    }

    /**
     * [getById description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getById($id)
    {
        $model  =   $this->model;

        $model  =   $model->where('id', '=', $id);

        $model  =   $model->first();

        return $model;
    }

}
