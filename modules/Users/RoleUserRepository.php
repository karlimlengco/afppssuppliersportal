<?php

namespace Revlv\Users;

use Datatables;
use \Revlv\Users\RoleUserEloquent;
use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class RoleUserRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return RoleUserEloquent::class;
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
