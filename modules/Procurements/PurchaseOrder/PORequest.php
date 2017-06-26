<?php

namespace Revlv\Procurements\PurchaseOrder;

use Revlv\BaseRequest;

class PORequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'rfq_id',
        'upr_id',
        'rfq_number',
        'upr_number',
        'purchase_date',
        'bid_amount',
        'payment_term',
        'prepared_by',
        'requestor_id',
        'accounting_id',
        'approver_id',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'rfq_id'            => 'required',
            'purchase_date'     => 'required',
            'payment_term'      => 'required',
        ];
    }
}