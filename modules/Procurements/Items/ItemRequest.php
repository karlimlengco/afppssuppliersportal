<?php

namespace Revlv\Procurements\Items;

use Revlv\BaseRequest;

class ItemRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'upr_id',
        'item_description',
        'quantity',
        'unit_measurement',
        'unit_price',
        'total_amount',
        'upr_number',
        'afpps_ref_number',
        'prepared_by',
        'date_prepared'
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'item_description'      => 'required',
            'quantity'              => 'required',
            'unit_price'            => 'required',
            'unit_measurement'      => 'required',
        ];
    }
}