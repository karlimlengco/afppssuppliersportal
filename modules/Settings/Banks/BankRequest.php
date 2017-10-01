<?php

namespace Revlv\Settings\Banks;

use Revlv\BaseRequest;

class BankRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'code',
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
            'code'              => 'required',
            'description'       => 'required',
        ];
    }
}