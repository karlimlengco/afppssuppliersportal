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
        'first_name',
        'middle_name',
        'surname',
        'contact_number',
        'address',
        'gender',
        'avatar',
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
            'username'              => 'required|min:5|unique:users,username,' . $this->user . ',username',
            'email'                 => 'required|unique:users,email,'. $this->user . ',username',
            'first_name'            => 'required',
            'middle_name'           => 'required',
            'surname'               => 'required',
            'contact_number'        => 'required',
            'gender'                => 'required',
            'address'               => 'required',
        ];
    }
}