<?php

namespace Revlv\Settings\CateredUnits;

use Revlv\BaseRequest;

class CateredUnitRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'pcco_id',
        'short_code',
        'description',
        'coa_address',
        'coa_address_2',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'short_code'    => 'required',
            'description'   => 'required',
            'pcco_id'       => 'required',
            'coa_address'   => 'required',
        ];
    }
}