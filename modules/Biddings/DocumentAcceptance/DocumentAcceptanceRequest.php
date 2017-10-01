<?php

namespace Revlv\Biddings\DocumentAcceptance;

use Revlv\BaseRequest;

class DocumentAcceptanceRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'upr_id',
        'bac_id',
        'upr_number',
        'ref_number',
        'transaction_date',
        'pre_proc_date',
        'approved_date',
        'return_date',
        'return_remarks',
        'remarks',
        'action',
        'update_remarks',
        'days',
        'processed_by',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bac_id'                        => 'required',
            'upr_id'                        => 'required',
            'approved_date'                 => 'required_without:return_date',
            'pre_proc_date'                 => 'required_without:return_date',
            'return_date'                   => 'required_without:approved_date',
            'return_remarks'                => 'required_with:return_date',
        ];
    }
}