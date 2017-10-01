<?php

namespace Revlv\Settings\ProcurementTypes;

use Revlv\BaseRequest;

class ProcurementTypeRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'code',
        'description',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code'          => 'required',
            'description'       => 'required',
        ];
    }
}