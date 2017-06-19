<?php

namespace Revlv\Procurements\DeliveryOrder;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class DeliveryOrderRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return DeliveryOrderEloquent::class;
    }

    /**
     * Return the model by its key valued pair
     *
     * @param string $id
     * @param string $value
     * @return mixed
     */
    public function listCompleted($id = 'id', $value = 'name')
    {
        $model =    $this->model;
        $model =    $model->whereStatus('completed');

        return $model->pluck($value, $id)->all();
    }
}
