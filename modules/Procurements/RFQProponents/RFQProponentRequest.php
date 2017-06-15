<?php

namespace Revlv\Procurements\RFQProponents;

use Revlv\BaseRequest;

class RFQProponentRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'rfq_id',
        'proponents',
        'note',
        'date_processed',
        'prepared_by',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'rfq_id'                => 'required',
            'proponents'            => 'required',
            'date_processed'        => 'required',
        ];
    }
}