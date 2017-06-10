<?php

namespace Revlv\Settings\ProcurementCenters;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class ProcurementCenterRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ProcurementCenterEloquent::class;
    }
}
