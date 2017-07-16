<?php

namespace Revlv\Biddings\PreBid;

use Revlv\BaseRequest;

class PreBidRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'upr_id',
        'upr_number',
        'ref_number',
        'transaction_date',
        'is_scb_issue',
        'is_resched',
        'bid_opening_date',
        'resched_date',
        'resched_remarks',
        'remarks',
        'update_remarks',
        'days',
        'processed_by',
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
            'transaction_date'              => 'required',
            'is_scb_issue'                  => 'required_without:is_resched',
            'is_resched'                    => 'required_without:is_scb_issue',
            'bid_opening_date'              => 'required_if:is_scb_issue,1',
            'resched_date'                  => 'required_if:is_resched,1',
            'resched_remarks'               => 'required_with:resched_date',
        ];
    }
}