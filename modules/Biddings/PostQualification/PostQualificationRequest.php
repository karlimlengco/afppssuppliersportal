<?php

namespace Revlv\Biddings\PostQualification;

use Revlv\BaseRequest;

class PostQualificationRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'upr_id',
        'upr_number',
        'ref_number',
        'transaction_date',
        'is_failed',
        'failed_remarks',
        'remarks',
        'proponent_id',
        'update_remarks',
        'days',
        'processed_by',
        'action',
        'date_failed',
        'disqualification_date',
        'disqualification_remarks',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pq_transaction_date'              => 'required',
            'failed_remarks'                => 'required_if:is_failed,1',
        ];
    }
}