<?php

namespace Revlv\Procurements\InspectionAndAcceptance;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class InspectionAndAcceptanceRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return InspectionAndAcceptanceEloquent::class;
    }
}
