<?php

namespace Revlv\Procurements\PhilGepsPosting;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class PhilGepsPostingRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PhilGepsPostingEloquent::class;
    }
}
