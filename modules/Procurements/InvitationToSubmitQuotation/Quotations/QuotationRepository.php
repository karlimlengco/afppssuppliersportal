<?php

namespace Revlv\Procurements\InvitationToSubmitQuotation\Quotations;

use Revlv\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class QuotationRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return QuotationEloquent::class;
    }
}
