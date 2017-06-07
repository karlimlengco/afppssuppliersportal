<?php

namespace Revlv\Sentinel\Permissions;

use Revlv\BaseRequest;

class PermissionRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'description',
        'permission'
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
            'permission'    => 'required|unique:permissions,permission,' . $this->route('permission'),
            'description'   => 'required',
        ];
    }
}