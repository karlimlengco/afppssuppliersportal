<?php

namespace Revlv\Procurements\InspectionAndAcceptance\Invoices;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class InvoiceRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return InvoiceEloquent::class;
    }
}
