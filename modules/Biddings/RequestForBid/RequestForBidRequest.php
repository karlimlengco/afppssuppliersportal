<?php

namespace Revlv\Biddings\RequestForBid;

use Revlv\BaseRequest;

class RequestForBidRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'upr_number',
        'upr_id',
        'rfb_number',

        'bac_id',

        'rfb_transaction_date',
        'released_date',

        'received_date',
        'received_by',

        'status',
        'remarks',
        'update_remarks',
        'processed_by',

        'days',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'upr_id'                => 'required',
            'bac_id'                => 'required',
            'released_date'         => 'required',
            'rfb_transaction_date'  => 'required',
        ];
    }
}