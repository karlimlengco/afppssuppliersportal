<?php

namespace Revlv\Procurements\Items;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class ItemRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ItemEloquent::class;
    }
}
