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
        'adjourned_time',
        'closebox_time',
        'order_time',
        'rfq_id',
        'canvass_time',
        'rfq_number',
        'update_remarks',
        'upr_number',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'canvass_date'              => 'required',
            // 'rfq_id'                    => 'required',
        ];
    }
}