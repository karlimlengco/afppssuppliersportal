<?php

namespace Revlv\Settings\MasterLists;

use Revlv\BaseRequest;

class MasterListRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'name',
        'file_name',
        'file',
        'user_id'
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                  => 'required|unique:master_lists|max:25',
            'file'                  => 'required',
        ];
    }
}