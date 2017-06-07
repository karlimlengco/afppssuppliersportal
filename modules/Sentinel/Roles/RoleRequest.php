<?php

namespace Revlv\Sentinel\Roles;

use Revlv\BaseRequest;

class RoleRequest extends BaseRequest
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
            // 'permissions'   => 'required',
            'slug'          => 'required|unique:roles,slug,' . $this->role,
            'name'          => 'required|unique:roles,name,' . $this->role,
        ];
    }
}