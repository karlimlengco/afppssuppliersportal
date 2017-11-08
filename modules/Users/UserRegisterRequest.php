<?php

namespace Revlv\Users;

use Revlv\BaseRequest;

class UserRegisterRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'email',
        'firstname',
        'lastname',
        'address1',
        'state',
        'countryid',
        'telephone',
        'password',
    ];

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'countryid.required' => 'The Country Field is required',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'                 => 'required',
            'password'              => 'required|min:6|confirmed',
            'firstname'             => 'required',
            'lastname'              => 'required',
            'telephone'             => 'required',
            'gender'                => 'required',
            'address1'              => 'required',
            'state'                 => 'required',
            'countryid'             => 'required',
        ];
    }
}