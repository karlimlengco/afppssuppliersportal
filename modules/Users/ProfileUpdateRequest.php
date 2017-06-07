<?php

namespace Revlv\Users;

use Revlv\BaseRequest;

class ProfileUpdateRequest extends BaseRequest
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
        'gender',
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
            'email'                 => 'required|unique:users,email,'. $this->id,
            'firstname'             => 'required',
            'lastname'              => 'required',
            'telephone'             => 'required',
            'gender'                => 'required',
            'address1'              => 'required',
            'state'                 => 'required',
            'gender'                => 'required',
            'countryid'             => 'required',
        ];
    }
}