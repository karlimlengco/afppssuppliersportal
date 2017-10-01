<?php

namespace Revlv\Biddings\InvitationToBid;

use Revlv\BaseRequest;

class InvitationToBidRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'upr_id',
        'upr_number',
        'ref_number',
        'transaction_date',
        'approved_date',
        'itb_approved_date',
        'approved_by',
        'remarks',
        'update_remarks',
        'days',
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
            'itb_approved_date'             => 'required',
        ];
    }
}