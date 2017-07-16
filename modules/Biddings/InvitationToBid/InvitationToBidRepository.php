<?php

namespace Revlv\Biddings\InvitationToBid;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class InvitationToBidRepository extends BaseRepository
{
    use  DatatableTrait;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return InvitationToBidEloquent::class;
    }

}
