<?php

namespace Revlv\Settings\PaymentTerms;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class PaymentTermRepository extends BaseRepository
{
    use  DatatableTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PaymentTermEloquent::class;
    }
}
