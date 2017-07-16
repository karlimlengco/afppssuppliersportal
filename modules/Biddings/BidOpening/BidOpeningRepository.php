<?php

namespace Revlv\Biddings\BidOpening;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class BidOpeningRepository extends BaseRepository
{
    use  DatatableTrait;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return BidOpeningEloquent::class;
    }

}
