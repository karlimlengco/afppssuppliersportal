<?php

namespace Revlv\Procurements\DeliveryInspection;

use Revlv\BaseRequest;

class DeliveryInspectionRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'dr_id',
        'upr_id',
        'rfq_id',
        'rfq_number',
        'upr_number',
        'delivery_number',
        'inspection_number',
        'start_date',
        'closed_date',
        'started_by',
        'closed_by',
        'received_by',
        'approved_by',
        'issued_by',
        'update_remarks',
        'requested_by',
        'days',
        'close_days',
        'remarks',
        'close_remarks',
        'action',
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
            'dr_id'         => 'required',
        ];
    }
}