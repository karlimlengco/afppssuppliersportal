<?php

namespace Revlv\Procurements\PhilGepsPosting;

use Revlv\BaseRequest;

class UpdateRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'rfq_id',
        'philgeps_number',
        'upr_number',
        'transaction_date',
        'philgeps_posting',
        'deadline_rfq',
        'update_remarks',
        'opening_time',
        'newspaper',
        'remarks',
        'action',
        'days',
        'status',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'philgeps_number'   => 'required',
            'transaction_date'  => 'required',
            'philgeps_posting'  => 'required',
            'deadline_rfq'      => 'required',
            'opening_time'      => 'required',
        ];
    }
}