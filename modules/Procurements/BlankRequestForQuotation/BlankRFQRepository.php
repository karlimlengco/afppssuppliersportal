<?php

namespace Revlv\Procurements\BlankRequestForQuotation;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class BlankRFQRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return BlankRFQEloquent::class;
    }
}
