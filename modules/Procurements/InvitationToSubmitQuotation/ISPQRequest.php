<?php

namespace Revlv\Procurements\InvitationToSubmitQuotation;

use Revlv\BaseRequest;

class ISPQRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'rfq_id',
        'upr_number',
        'rfq_number',
        'venue',
        'transaction_date',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'items'             => 'required',
            'venue'             => 'required',
            'transaction_date'  => 'required',
        ];
    }
}