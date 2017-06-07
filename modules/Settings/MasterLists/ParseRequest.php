<?php

namespace Revlv\Settings\MasterLists;

use Revlv\BaseRequest;

class ParseRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'file_name',
        'file',
        'months',
        'start_date',
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
            'months'                => 'required|int',
            'start_date'            => 'required|date|date_format:Y-m',
            'file'                  => 'required',
        ];
    }
}