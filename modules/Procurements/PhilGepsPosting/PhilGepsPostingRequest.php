<?php

namespace Revlv\Procurements\PhilGepsPosting;

use Revlv\BaseRequest;

class PhilGepsPostingRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'upr_id',
        'upr_number',
        'philgeps_number',
        'transaction_date',
        'philgeps_posting',
        'deadline_rfq',
        'opening_time',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'upr_id'            => 'required',
            'philgeps_number'   => 'required',
            'transaction_date'  => 'required',
            'philgeps_posting'  => 'required',
            'deadline_rfq'      => 'required',
            'opening_time'      => 'required',
        ];
    }
}