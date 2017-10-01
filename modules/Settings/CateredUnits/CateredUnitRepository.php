<?php

namespace Revlv\Settings\CateredUnits;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class CateredUnitRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return CateredUnitEloquent::class;
    }
}
