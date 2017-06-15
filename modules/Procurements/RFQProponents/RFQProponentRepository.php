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

}
