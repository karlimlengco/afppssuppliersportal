<?php

namespace Revlv\Procurements\InspectionAndAcceptance;

use Revlv\BaseRequest;

class InspectionAndAcceptanceRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'rfq_id',
        'upr_id',
        'dr_id',
        'delivery_number',
        'rfq_number',
        'upr_number',
        'inspection_date',
        'nature_of_delivery',
        'findings',
        'prepared_by',
        'status',
        'update_remarks',
        'recommendation',
        'days',
        'accept_days',
        'remarks',
        'accept_remarks',
        'sao_signatory',
        'action',
        'accept_action',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dr_id'                 => 'required',
            'inspection_date'       => 'required',
        ];
    }
}