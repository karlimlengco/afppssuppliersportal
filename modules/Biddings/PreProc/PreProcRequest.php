<?php

namespace Revlv\Biddings\PreProc;

use Revlv\BaseRequest;

class PreProcRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'upr_id',
        'upr_number',
        'ref_number',
        'pre_proc_date',
        'resched_date',
        'resched_remarks',
        'remarks',
        'action',
        'update_remarks',
        'days',
        'processed_by',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pre_proc_date'                  => 'required',
            'resched_date'                   => 'required_without:pre_proc_date',
            'resched_remarks'                => 'required_with:resched_date',
        ];
    }
}