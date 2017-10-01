<?php

namespace Revlv\Settings\Units;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class UnitRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return UnitEloquent::class;
    }
}
