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
}
