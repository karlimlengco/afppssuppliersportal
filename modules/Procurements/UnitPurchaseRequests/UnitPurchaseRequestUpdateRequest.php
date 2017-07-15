<?php

namespace Revlv\Procurements\UnitPurchaseRequests;

use Revlv\BaseRequest;

class UnitPurchaseRequestUpdateRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'place_of_delivery',
        'procurement_office',
        'mode_of_procurement',
        'chargeability',
        'old_account_code',
        'new_account_code',
        'procurement_type',
        'total_amount',

        'fund_validity',
        'terms_of_payment',
        'other_infos',

        'units',
        'purpose',

        'project_name',
        'upr_number',
        'ref_number',

        'date_prepared',
        'prepared_by',

        'completed_at',

        'cancelled_at',
        'cancel_reason',

        'remarks',
        'update_remarks',

        'date_processed',
        'processed_by',

        'terminated_date',
        'terminate_status',
        'terminated_by',

        'requestor_id',
        'fund_signatory_id',
        'approver_id',

        'status',
        'state',

        'days',
        'delay_count',
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
            'new_account_code'      => 'required|integer',
            'date_prepared'         => 'required|date',
        ];
    }
}