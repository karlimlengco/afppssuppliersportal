<?php

namespace Revlv\Biddings\PreProc;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class PreProcRepository extends BaseRepository
{
    use  DatatableTrait;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PreProcEloquent::class;
    }

}