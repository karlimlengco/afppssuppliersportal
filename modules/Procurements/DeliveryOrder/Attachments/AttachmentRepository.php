<?php

namespace Revlv\Procurements\DeliveryOrder\Attachments;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class AttachmentRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return AttachmentEloquent::class;
    }
}
