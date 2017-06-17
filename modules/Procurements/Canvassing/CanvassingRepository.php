<?php

namespace Revlv\Procurements\Canvassing;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class CanvassingRepository extends BaseRepository
{
    use  DatatableTrait;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return CanvassingEloquent::class;
    }

}
