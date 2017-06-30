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
        'chargeability',
        'account_code',

        'fund_validity',
        'terms_of_payment',
        'update_remarks',
        'other_infos',

        'units',
        'purpose',

        'project_name',
        'upr_number',
        'ref_number',

        'date_prepared',
        'prepared_by',

        'date_processed',
        'processed_by',

        'total_amount',

        'status',
        'state',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'project_name'          => 'required',
            'upr_number'            => 'required',
            'update_remarks'        => 'required',
            'place_of_delivery'     => 'required|integer',
            'mode_of_procurement'   => 'required|integer',
            'chargeability'         => 'required|integer',
            'units'                 => 'required|integer',
            'purpose'               => 'required',
            'account_code'          => 'required|integer',
            'date_prepared'         => 'required|date',
        ];
    }
}