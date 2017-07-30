<?php

namespace Revlv\Biddings\DocumentAcceptance;

use Revlv\BaseRequest;

class UpdateRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'upr_number',
        'ref_number',
        'transaction_date',
        'approved_date',
        'resched_date',
        'resched_remarks',
        'remarks',
        'action',
        'days',
        'update_remarks',
        'processed_by',
        'bac_id',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bac_id'                        => 'required',
            'transaction_date'              => 'required',
            'approved_date'                 => 'required_without:resched_date',
            'resched_date'                  => 'required_without:approved_date',
            'resched_remarks'               => 'required_with:resched_date',
        ];
    }
}