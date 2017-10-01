<?php

namespace Revlv\Settings\Banks;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class BankRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return BankEloquent::class;
    }
}
