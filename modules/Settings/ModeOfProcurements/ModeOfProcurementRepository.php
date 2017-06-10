<?php

namespace Revlv\Settings\ModeOfProcurements;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class ModeOfProcurementRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ModeOfProcurementEloquent::class;
    }
}
