<?php

namespace Revlv\Users;

use Revlv\BaseRequest;

class ProfileRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'email',
        'contact_number',
        'address',
        'avatar',
        'gender',
        'first_name',
        'middle_name',
        'password',
        'surname',
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
            'email'                 => 'required|unique:users,email,'. \Sentinel::getUser()->username . ',username',
            'contact_number'        => 'required',
            'address'               => 'required',
            'first_name'            => 'required',
            'middle_name'           => 'required',
            'surname'               => 'required',
            'password'              => 'confirmed',
        ];
    }
}