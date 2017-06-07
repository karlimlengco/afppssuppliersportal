<?php

namespace Revlv\Sentinel\Roles;

use Revlv\BaseRequest;

class RoleCreateRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'slug',
        'name',
        'permissions'
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
            'name'          => 'required|unique:roles,name',
            'slug'          => 'required|unique:roles,slug',
            'permissions'   => 'required',
        ];
    }
}