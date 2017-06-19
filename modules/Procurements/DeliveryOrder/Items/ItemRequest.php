<?php

namespace Revlv\Procurements\DeliveryOrder\Items;

use Revlv\BaseRequest;

class ItemRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'order_id',
        'description',
        'quantity',
        'unit',
        'price_unit',
        'total_amount',
        'received_quantity',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'received_quantity' => 'required',
        ];
    }
}