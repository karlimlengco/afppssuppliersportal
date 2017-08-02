<?php

namespace Revlv\Users;

use Revlv\BaseRequest;

class UserCreateRequest extends BaseRequest
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
     * Set custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'password.required_without' => 'The :attribute field is required.',
            'password_confirmation.required_without' => 'The :attribute field is required.',
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
            'username'              => 'required|min:5|max:25|unique:users',
            'first_name'            => 'required',
            'email'                 => 'email',
            'password'              => 'required|confirmed',
            'password_confirmation' => 'required',
            'middle_name'           => 'required',
            'surname'               => 'required',
            'contact_number'        => 'required',
            'gender'                => 'required',
            'address'               => 'required',
            'unit_id'               => 'required',
            'designation'           => 'required',
        ];
    }
}