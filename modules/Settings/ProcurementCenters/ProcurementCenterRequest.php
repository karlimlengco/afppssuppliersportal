<?php

namespace Revlv\Settings\ProcurementCenters;

use Revlv\BaseRequest;

class ProcurementCenterRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'name',
        'address',
        'programs',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => 'required',
            'address'       => 'required',
            'programs'      => 'required',
        ];
    }
}