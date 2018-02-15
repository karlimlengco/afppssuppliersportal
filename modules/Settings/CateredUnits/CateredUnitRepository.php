<?php

namespace Revlv\Settings\CateredUnits;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class CateredUnitRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return CateredUnitEloquent::class;
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


    /**
     * [findByRFQId description]
     *
     * @param  [type] $rfq [description]
     * @return [type]      [description]
     */
    public function getByCode($id)
    {
        $model  =    $this->model;

        $model  =   $model->where('short_code', '=', $id);

        return $model->first();
    }


    /**
     * [findByDescription description]
     *
     * @param  [type] $rfq [description]
     * @return [type]      [description]
     */
    public function findByDescription($desc)
    {
        $model  =    $this->model;

        $model  =   $model->where('description', '=', $desc);

        return $model->first();
    }
}
