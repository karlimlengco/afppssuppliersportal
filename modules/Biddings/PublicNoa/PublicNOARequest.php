<?php

namespace Revlv\Biddings\PublicNoa;

use Revlv\BaseRequest;

class PublicNOARequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'rfb_id',
        'upr_id',
        'supplier_id',
        'upr_number',
        'rfb_number',

        'received_noa',
        'received_by',

        'approved_noa',

        'supplier_received_noa',
        'supplier_received_by',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'rfb_id'                => 'required',
            'received_noa'          => 'required',
            'supplier_id'           => 'required',
            'received_by'           => 'required',
        ];
    }
}