<?php

namespace Revlv\Settings\Forms\Header;

use Revlv\BaseRequest;

class HeaderRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'unit_id',
        'content',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'              => 'required',
        ];
    }
}