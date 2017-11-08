<?php

namespace Revlv\Users;

use Revlv\BaseRequest;

class UserRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'email',
        'username',
        'password',

        'permissions',
        'avatar',

        'last_login',

        'contact_number',
        'address',

        'unit_id',
        'designation',

        'first_name',
        'middle_name',
        'surname',

        'gender',
        'status',

        'birthday',

        'username',
        'email',
        'password',
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
            'unit_id'               => 'required',
            'designation'           => 'required',
            'first_name'            => 'required',
            'surname'               => 'required',
            'contact_number'        => 'required',
            'gender'                => 'required',
            'address'               => 'required',
            'password'              => 'confirmed',
        ];
    }
}