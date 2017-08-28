<?php

namespace Revlv\Procurements\Canvassing;

use Revlv\BaseRequest;

class CanvassingRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'canvass_date',
        'canvass_time',
        'adjourned_time',
        'closebox_time',
        'order_time',
        'rfq_id',
        'canvass_time',
        'rfq_number',
        'update_remarks',
        'remarks',
        'upr_number',
        'chief',
        'presiding_officer',
        'other_attendees',
        'action',
        'resolution',
        'days',

        'chief_signatory',
        'presiding_signatory',
        'unit_head_signatory',
        'mfo_signatory',
        'legal_signatory',
        'secretary_signatory',

        'unit_head',
        'mfo',
        'legal',
        'secretary',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'unit_head'              => 'required',
            'mfo'              => 'required',
            'secretary'              => 'required',
            'legal'              => 'required',
            'chief'              => 'required',
            'presiding_officer'              => 'required',
            // 'rfq_id'                    => 'required',
        ];
    }
}