<?php

namespace Revlv\Biddings\BidDocs;

use Revlv\BaseRequest;

class BidDocsRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'upr_id',
        'upr_number',
        'ref_number',
        'transaction_date',
        'bid_transaction_date',
        'proponent_id',
        'proponent_name',
        'processed_by',
        'remarks',
        'days',
        'is_lcb',
        'is_scb',
        'status',
        'bid_amount',
        'update_remarks',
        'action',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bid_transaction_date'          => 'required',
            'proponent_id'                  => 'required',
            'upr_id'                        => 'required',
        ];
    }
}