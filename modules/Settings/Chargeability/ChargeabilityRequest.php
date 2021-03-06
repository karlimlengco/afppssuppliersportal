<?php

namespace Revlv\Settings\Chargeability;

use Revlv\BaseRequest;

class ChargeabilityRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'name',
        'description',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'              => 'required',
        ];
    }
}