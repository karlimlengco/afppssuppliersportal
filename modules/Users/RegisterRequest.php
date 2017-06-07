<?php

namespace Revlv\Users;

use Revlv\BaseRequest;

class RegisterRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'employee_number',
        'email',
        'username',
        'first_name',
        'middle_name',
        'surname',
        'birthday',
        'gender',
        'address',
        'contact_number',
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
            'employee_number'       => 'required|unique:users',
            'username'              => 'required|min:5|max:25|unique:users',
            'email'                 => 'required|unique:users',
            'first_name'            => 'required',
            'middle_name'           => 'required',
            'surname'               => 'required',
            'gender'                => 'required',
            'address'               => 'required',
            'birthday'              => 'required',
            'contact_number'        => 'required',
        ];
    }
}