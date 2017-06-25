<?php

namespace Revlv\Procurements\BlankRequestForQuotation;

use Revlv\BaseRequest;

class UpdateRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'upr_id',

        'upr_number',
        'rfq_number',

        'deadline',
        'opening_time',
        'completed_at',
        'transaction_date',

        'status',
        'remarks',
        'processed_by',

        'awarded_to',
        'awarded_date',
        'is_award_accepted',
        'award_accepted_date',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'deadline'              => 'required',
            'opening_time'          => 'required',
            'transaction_date'      => 'required',
        ];
    }
}