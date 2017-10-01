<?php

namespace Revlv\Settings\AccountCodes;

use Revlv\BaseRequest;

class AccountCodeRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'name',
        'expense_class_id',
        'old_account_code',
        'new_account_code',
        'main_class',
        'sub_class',
        'account_group',
        'detailed_account',
        'contra_account',
        'sub_account',
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
            'new_account_code'  => 'required',
        ];
    }
}