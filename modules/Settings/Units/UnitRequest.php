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
            'description'   => 'required',
        ];
    }
}