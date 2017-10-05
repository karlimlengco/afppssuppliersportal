<?php

namespace Revlv\Procurements\NoticeToProceed;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class NTPRepository extends BaseRepository
{
    use  DatatableTrait;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return NTPEloquent::class;
    }

    /**
     * [findByRFQ description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function findByRFQ($id)
    {
        $model  =   $this->model;

        $model  =   $model->where('rfq_id','=', $id);

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
