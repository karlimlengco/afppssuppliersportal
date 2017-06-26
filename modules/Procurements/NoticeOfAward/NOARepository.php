<?php

namespace Revlv\Procurements\NoticeOfAward;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class NOARepository extends BaseRepository
{
    use  DatatableTrait;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return NOAEloquent::class;
    }

}
