<?php

namespace Revlv\Procurements\PurchaseOrder\Items;

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
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description'   => 'required',
            'quantity'      => 'required',
            'unit'          => 'required',
            'price_unit'    => 'required',
            'total_amount'  => 'required',
        ];
    }
}