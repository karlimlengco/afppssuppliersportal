<?php

namespace Revlv\Procurements\DeliveryInspection;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class DeliveryInspectionRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return DeliveryInspectionEloquent::class;
    }

}
