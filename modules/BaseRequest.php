<?php

namespace Revlv;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
    * Returns data only that matches the whitelisted array
    *
    * @return array
    */
    public function getData()
    {
        return array_only($this->all(), $this->whitelist);
    }
}
