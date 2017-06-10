<?php

namespace Revlv\Settings\AccountCodes;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class AccountCodeRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AccountCodeEloquent::class;
    }
}
