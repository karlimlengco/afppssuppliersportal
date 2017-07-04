<?php

namespace Revlv\Procurements\PhilGepsPosting;

use Revlv\BaseRequest;

class PhilGepsPostingRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'rfq_id',
        'philgeps_number',
        'rfq_number',
        'upr_number',
        'transaction_date',
        'philgeps_posting',
        'deadline_rfq',
        'update_remarks',
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
            'rfq_id'            => 'required',
            'philgeps_number'   => 'required',
            'transaction_date'  => 'required',
            'philgeps_posting'  => 'required',
            'deadline_rfq'      => 'required',
            'opening_time'      => 'required',
        ];
    }
}