<?php

namespace Revlv\Biddings\PublicNoa;

use DB;
use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class PublicNOARepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PublicNOAEloquent::class;
    }

}
