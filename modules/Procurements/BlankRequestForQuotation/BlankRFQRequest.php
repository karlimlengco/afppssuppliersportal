<?php

namespace Revlv\Procurements\BlankRequestForQuotation;

use Revlv\BaseRequest;

class BlankRFQRequest extends BaseRequest
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
        'transaction_date',

        'status',
        'remarks',
        'action',
        'processed_by',

        'awarded_to',
        'awarded_date',
        'completed_at',
        'is_award_accepted',
        'award_accepted_date',
        'days',
        'close_days',
        'close_remarks',
        'close_action',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'upr_id'                => 'required',
            // 'deadline'              => 'required',
            // 'opening_time'          => 'required',
            'transaction_date'      => 'required',
        ];
    }
}