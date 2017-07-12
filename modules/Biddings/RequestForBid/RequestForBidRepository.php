<?php

namespace Revlv\Biddings\RequestForBid;

use DB;
use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class RequestForBidRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return RequestForBidEloquent::class;
    }

}
