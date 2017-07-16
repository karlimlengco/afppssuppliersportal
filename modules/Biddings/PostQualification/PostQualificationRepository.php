<?php

namespace Revlv\Biddings\PostQualification;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class PostQualificationRepository extends BaseRepository
{
    use  DatatableTrait;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PostQualificationEloquent::class;
    }

}
