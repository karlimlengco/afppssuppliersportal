<?php

namespace Revlv\Events;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class NotificationRepository extends BaseRepository
{
    use DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return NotificationEloquent::class;
    }

}
