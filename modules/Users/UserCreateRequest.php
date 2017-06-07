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
        'first_name',
        'middle_name',
        'surname',
        'birthday',
        'contact_number',
        'address',
        'gender',
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
            'email'                 => 'required|unique:users',
            'first_name'            => 'required',
            'middle_name'           => 'required',
            'surname'               => 'required',
            'contact_number'        => 'required',
            'gender'                => 'required',
            'address'               => 'required',
        ];
    }
}