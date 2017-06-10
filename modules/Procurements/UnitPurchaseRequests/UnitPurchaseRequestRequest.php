<?php

namespace Revlv\Procurements\UnitPurchaseRequests;

use Revlv\BaseRequest;

class UnitPurchaseRequestRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'place_of_delivery',
        'mode_of_procurement',
        'chargability',
        'account_code',
        'fund_validity',
        'terms_of_payment',
        'other_infos',
        'upr_number',
        'purpose',
        'afpps_ref_number',
        'date_prepared',
        'total_amount',
        'prepared_by',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'place_of_delivery'     => 'required',
            'mode_of_procurement'   => 'required',
            'chargability'          => 'required',
            'account_code'          => 'required'
        ];
    }
}