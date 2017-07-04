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