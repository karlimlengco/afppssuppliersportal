<?php

namespace Revlv\Procurements\Vouchers;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class VoucherRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return VoucherEloquent::class;
    }

}
