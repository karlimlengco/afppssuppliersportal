<?php

namespace Revlv\Settings\Forms\PCCOHeader;

use Revlv\BaseRequest;

class PCCOHeaderRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'pcco_id',
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
            'content'              => 'required',
        ];
    }
}