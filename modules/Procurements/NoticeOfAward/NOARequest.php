<?php

namespace Revlv\Procurements\NoticeOfAward;

use Revlv\BaseRequest;

class NOARequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'rfq_id',
        'upr_id',
        'rfq_number',
        'upr_number',
        'signatory_id',
        'canvass_id',
        'proponent_id',
        'awarded_by',
        'awarded_date',
        'remarks',
        'received_by',
        'status',
        'award_accepted_date',
        'update_remarks',
        'seconded_by',
        'resolution',
        'accepted_date',

        'days',

        'approved_remarks',
        'approved_days',

        'received_remarks',
        'received_days',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'proponent_id'              => 'required',
        ];
    }
}