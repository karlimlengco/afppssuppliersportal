<?php

namespace Revlv\Settings\Suppliers;

use Revlv\BaseRequest;

class SupplierRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'name',
        'owner',
        'address',
        'tin',
        'bank_id',
        'branch',
        'account_number',
        'account_type',
        'cell_1',
        'cell_2',
        'phone_1',
        'phone_2',
        'fax_1',
        'fax_2',
        'email_1',
        'email_2',
        'status',
    ];

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'cell_1.required' => 'Cellphone # is required',
            'email_1.required'  => 'Email address is required',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'              => 'required',
            'owner'             => 'required',
            'address'           => 'required',
            'cell_1'            => 'required',
            'email_1'           => 'required',
        ];
    }
}