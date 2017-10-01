<?php

namespace Revlv\Procurements\InvitationToSubmitQuotation;

use Revlv\BaseRequest;

class UpdateRequest extends BaseRequest
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
        'update_remarks',
        'remarks',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'signatory_id'      => 'required',
            'venue'             => 'required',
            'transaction_date'  => 'required',
        ];
    }
}