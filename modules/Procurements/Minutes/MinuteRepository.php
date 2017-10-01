<?php

namespace Revlv\Procurements\Minutes;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class MinuteRepository extends BaseRepository
{
    use  DatatableTrait;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MinuteEloquent::class;
    }

}
