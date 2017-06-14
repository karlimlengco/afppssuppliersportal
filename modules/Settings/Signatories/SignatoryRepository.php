<?php

namespace Revlv\Settings\Signatories;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class SignatoryRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return SignatoryEloquent::class;
    }
}
