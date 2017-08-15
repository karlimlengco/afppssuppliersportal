<?php

namespace Revlv\Procurements\RFQProponents;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class RFQProponentRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return RFQProponentEloquent::class;
    }

    /**
     * [findByRFQId description]
     *
     * @param  [type] $rfq [description]
     * @return [type]      [description]
     */
    public function findByRFQId($rfq)
    {
        $model  =   $this->model;

        $model  =   $model->where('rfq_id', '=', $rfq);

        $model  =   $model->orderByRaw("CAST(bid_amount as UNSIGNED)");

        return $model->get();
    }

    /**
     * [findAwardeeByRFQId description]
     *
     * @param  [type] $rfq [description]
     * @return [type]      [description]
     */
    public function findAwardeeByRFQId($rfq)
    {
        $model  =   $this->model;

        $model  =   $model->where('rfq_id', '=', $rfq);
        $model  =   $model->whereNotNull('is_awarded');

        return $model->first();
    }

}
