<?php

namespace Revlv\Biddings\BidDocs;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class BidDocsRepository extends BaseRepository
{
    use  DatatableTrait;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return BidDocsEloquent::class;
    }

}
