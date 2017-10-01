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
}
