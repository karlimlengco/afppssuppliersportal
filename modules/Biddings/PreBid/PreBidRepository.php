<?php

namespace Revlv\Biddings\PreBid;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class PreBidRepository extends BaseRepository
{
    use  DatatableTrait;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PreBidEloquent::class;
    }

}