<?php

namespace Revlv\Procurements\NoticeToProceed;

use Revlv\BaseRequest;

class NTPRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'po_id',
        'rfq_id',
        'upr_id',
        'rfq_number',
        'upr_number',
        'signatory_id',
        'proponent_id',
        'prepared_by',
        'prepared_date',
        'status',
        'signatory',
        'remarks',
        'update_remarks',
        'file',

        'received_by',
        'award_accepted_date',
        'accepted_date',
        'days',
        'accepted_days',
        'accepted_remarks',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }
}