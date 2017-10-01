<?php

namespace Revlv\Settings\PaymentTerms;

use Revlv\BaseRequest;

class PaymentTermRequest extends BaseRequest
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
            'name'          => 'required',
            'description'   => 'required',
        ];
    }
}