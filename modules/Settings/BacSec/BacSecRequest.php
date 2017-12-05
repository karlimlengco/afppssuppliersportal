<?php

namespace Revlv\Settings\BacSec;

use Revlv\BaseRequest;

class BacSecRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'name',
        'description',
        'pcco_id',
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
            'pcco_id'           => 'required',
            'description'       => 'required',
        ];
    }
}