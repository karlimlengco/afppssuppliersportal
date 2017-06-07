<?php

namespace Revlv\Users;

use Revlv\BaseRequest;

class UserPasswordRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'password',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password'              => 'required|min:6|confirmed',
        ];
    }
}