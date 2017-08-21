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
        'philgeps_number',
        'rfq_number',
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
            'upr_id'            => 'required',
            'philgeps_number'   => 'required',
            'transaction_date'  => 'required',
            'philgeps_posting'  => 'required',
            'deadline_rfq'      => 'required',
            'status'            => 'required',
            'pp_opening_time'      => 'required',
        ];
    }
}