<?php

namespace Revlv\Settings\Units;

use Revlv\BaseRequest;

class UnitRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'name',
        'description',
        'pcco_id',
        'coa_address',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pcco_id'       => 'required',
            'name'          => 'required',
            'description'   => 'required',
            'coa_address'   => 'required',
        ];
    }
}