<?php

namespace Revlv\Settings\Suppliers;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class SupplierRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SupplierEloquent::class;
    }
}
