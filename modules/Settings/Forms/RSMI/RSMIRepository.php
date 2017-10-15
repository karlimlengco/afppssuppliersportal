<?php

namespace Revlv\Settings\Forms\RSMI;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class RSMIRepository extends BaseRepository
{
    use DatatableTrait;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return RSMIEloquent::class;
    }

    public function findByUnit($id)
    {
        $model  =   $this->model;

        $model  =   $model->where('unit_id','=', $id);

        $model  =   $model->first();

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
