<?php

namespace Revlv\Procurements\PurchaseOrder\Items;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class ItemRepository extends BaseRepository
{
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
