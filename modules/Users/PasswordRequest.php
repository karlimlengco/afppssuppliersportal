<?php

namespace Revlv\Users;

use Revlv\BaseRequest;

class PasswordRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
       'password',
       'username'
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password'       => 'required',
            'username'       => 'required',
        ];
    }
}