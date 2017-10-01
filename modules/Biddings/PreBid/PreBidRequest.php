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
        'failed_remarks',
        'resched_remarks',
        'remarks',
        'update_remarks',
        'days',
        'processed_by',
        'sbb_date',
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
            'sbb_date'                      => 'required_without:is_resched',
            'is_resched'                    => 'required_without:is_scb_issue',
            'bid_opening_date'              => 'required_if:is_resched,0',
            'resched_date'                  => 'required_if:is_resched,1',
            'resched_remarks'               => 'required_with:resched_date',
        ];
    }
}