<?php

namespace Revlv\Settings\MasterLists;

use Revlv\BaseRequest;

class MasterListParseRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'first_file',
        'second_file',
        'start_date',
        'months'
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_file'                => 'required_without:second_file',
            'second_file'               => 'required_without:first_file',
            'start_date'                => 'required|date|date_format:Y-m',
            'months'                    => 'required|int',
        ];
    }
}