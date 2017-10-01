<?php

namespace Revlv\Biddings\DocumentAcceptance;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class DocumentAcceptanceRepository extends BaseRepository
{
    use  DatatableTrait;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return DocumentAcceptanceEloquent::class;
    }

}
