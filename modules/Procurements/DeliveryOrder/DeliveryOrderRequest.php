<?php

namespace Revlv\Procurements\DeliveryOrder;

use Revlv\BaseRequest;

class DeliveryOrderRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'rfq_id',
        'upr_id',
        'rfq_number',
        'upr_number',
        'po_id',
        'delivery_date',
        'delivery_number',
        'transaction_date',
        'expected_date',
        'prepared_by',
        'created_by',
        'notes',
        'update_remarks',
        'days',
        'remarks',
        'delivery_days',
        'delivery_remarks',
        'dr_coa_days',
        'dr_coa_remarks',
        'action',
        'delivery_action',
        'dr_coa_action',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'delivery_date'     => 'required',
            'delivery_number'   => 'required',
        ];
    }
}