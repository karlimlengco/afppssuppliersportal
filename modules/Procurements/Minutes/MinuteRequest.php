<?php

namespace Revlv\Procurements\Minutes;

use Revlv\BaseRequest;

class MinuteRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'date_opened',
        'time_opened',
        'venue',
        'time_closed',
        'officer_id',
        'canvass',
        'prepared_by',
        'members',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date_opened'           => 'required',
            'time_opened'           => 'required',
            'officer_id'            => 'required',
            'members'               => 'required',
            'canvass'               => 'required',
            'venue'                 => 'required',
            'time_closed'           => 'required',
        ];
    }
}