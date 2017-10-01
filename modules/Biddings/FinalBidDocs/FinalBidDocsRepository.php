<?php

namespace Revlv\Biddings\FinalBidDocs;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class FinalBidDocsRepository extends BaseRepository
{
    use  DatatableTrait;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return FinalBidDocsEloquent::class;
    }

}
