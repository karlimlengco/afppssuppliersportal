<?php

namespace Revlv\Biddings\BidOpening;

use Revlv\BaseRequest;

class BidOpeningRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'upr_id',
        'upr_number',
        'ref_number',
        'op_transaction_date',
        'closing_date',
        'is_completed',
        'days',
        'remarks',
        'processed_by',
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
            'op_transaction_date'           => 'required',
        ];
    }
}