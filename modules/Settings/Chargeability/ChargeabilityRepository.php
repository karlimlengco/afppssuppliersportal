<?php

namespace Revlv\Settings\Chargeability;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class ChargeabilityRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ChargeabilityEloquent::class;
    }
}
