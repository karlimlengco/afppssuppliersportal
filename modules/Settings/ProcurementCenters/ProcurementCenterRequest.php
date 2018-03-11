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
        'short_code',
        'telephone_number',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => 'required|unique:procurement_centers,name,'. $this->route('procurement_center'),
            'address'       => 'required',
            'programs'      => 'required',
            'short_code'    => 'required',
        ];
    }
}