<?php

namespace Revlv\Settings\Signatories;

use Revlv\BaseRequest;

class SignatoryRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'name',
        'designation',
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
            'designation'       => 'required',
        ];
    }
}