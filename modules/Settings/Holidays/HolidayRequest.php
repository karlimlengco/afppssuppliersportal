<?php

namespace Revlv\Settings\Holidays;

use Revlv\BaseRequest;

class HolidayRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'holiday_date',
        'name',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'holiday_date'              => 'required',
        ];
    }
}