<?php

namespace Revlv\Biddings\FinalBidDocs;

use Revlv\BaseRequest;

class FinalBidDocsRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'upr_id',
        'upr_number',
        'ref_number',
        'transaction_date',
        'remarks',
        'days',
        'update_remarks',
        'action',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'transaction_date'              => 'required',
        ];
    }
}