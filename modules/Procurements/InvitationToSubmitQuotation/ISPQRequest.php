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
        'signatory_id',
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
            'signatory_id'      => 'required',
            'venue'             => 'required',
            'transaction_date'  => 'required',
        ];
    }
}