<?php

namespace Revlv\Procurements\PurchaseOrder;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class PORepository extends BaseRepository
{
    use  DatatableTrait, NTPTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return POEloquent::class;
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
