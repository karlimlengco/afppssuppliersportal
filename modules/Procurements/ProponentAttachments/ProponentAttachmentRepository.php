<?php

namespace Revlv\Procurements\ProponentAttachments;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class ProponentAttachmentRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ProponentAttachmentEloquent::class;
    }

}
