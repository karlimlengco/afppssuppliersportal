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
        'update_remarks',

        'status',
        'remarks',
        'processed_by',

        'awarded_to',
        'awarded_date',
        'is_award_accepted',
        'chief',
        'signatory_chief',
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
            'transaction_date'      => 'required',
            'chief'      => 'required',
        ];
    }
}