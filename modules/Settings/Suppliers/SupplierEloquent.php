<?php

namespace Revlv\Settings\Suppliers;

use Illuminate\Database\Eloquent\Model;

class SupplierEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'suppliers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
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
    ];

}
