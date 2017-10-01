<?php

namespace Revlv\Settings\Forms\Header;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class HeaderRepository extends BaseRepository
{
    use DatatableTrait;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return HeaderEloquent::class;
    }

    /**
     * [findByUnit description]
     *
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function findByUnit($id)
    {
        $model  =   $this->model;

        $model  =   $model->where('unit_id','=', $id);

        $model  =   $model->first();

        return $model;
    }
}
