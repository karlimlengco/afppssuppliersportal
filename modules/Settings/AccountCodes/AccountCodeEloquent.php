<?php

namespace Revlv\Settings\AccountCodes;

use Illuminate\Database\Eloquent\Model;

class AccountCodeEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'account_codes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
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

}
