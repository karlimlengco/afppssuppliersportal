<?php

namespace Revlv\Procurements\UnitPurchaseRequests;

use Revlv\BaseRequest;

class UnitPurchaseRequestUpdateRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'procurement_office',
        'mode_of_procurement',
        'chargeability',
        'account_code',
        'place_of_delivery',

        'fund_validity',
        'terms_of_payment',
        'update_remarks',
        'other_infos',

        'units',
        'purpose',
        'cancelled_at',
        'cancel_reason',

        'project_name',
        'completed_at',
        'days',
        'upr_number',
        // 'ref_number',

        'date_prepared',
        'prepared_by',
        'procurement_type',

        'date_processed',
        'processed_by',

        'total_amount',

        'status',
        'state',

        'requestor_id',
        'fund_signatory_id',
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
            'project_name'          => 'required',
            'upr_number'            => 'required|unique:unit_purchase_requests,upr_number,'.$this->route('unit_purchase_request'),
            'place_of_delivery'     => 'required',
            'procurement_office'    => 'required|integer',
            'mode_of_procurement'   => 'required',
            'procurement_type'      => 'required|integer',
            'chargeability'         => 'required|integer',
            'units'                 => 'required|integer',
            'purpose'               => 'required',
            'account_code'          => 'required|integer',
            'date_prepared'         => 'required|date',
        ];
    }
}