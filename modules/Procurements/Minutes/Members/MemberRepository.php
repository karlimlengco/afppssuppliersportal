<?php

namespace Revlv\Procurements\Minutes\Members;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class MemberRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MemberEloquent::class;
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

}
