<?php

namespace Revlv\Procurements\Vouchers;

use Revlv\BaseRequest;

class VoucherRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'upr_id',
        'rfq_id',
        'prepared_by',
        'rfq_number',
        'upr_number',
        'transaction_date',
        'bir_address',
        'final_tax',
        'expanded_witholding_tax',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'rfq_id'                => 'required',
            'transaction_date'      => 'required',
            'bir_address'           => 'required',
        ];
    }
}