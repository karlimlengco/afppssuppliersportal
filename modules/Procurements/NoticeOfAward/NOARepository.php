<?php

namespace Revlv\Procurements\NoticeOfAward;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class NOARepository extends BaseRepository
{
    use  DatatableTrait;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return NOAEloquent::class;
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
     * [findByUPR description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function findByUPR($id)
    {
        $model  =   $this->model;

        $model  =   $model->where('upr_id','=', $id);

        $model  =   $model->orderBy('created_at','desc');

        $model  =   $model->first();

        return $model;
    }
}
