<?php

namespace Revlv\Procurements\InvitationToSubmitQuotation;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class ISPQRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ISPQEloquent::class;
    }
}
