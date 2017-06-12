<?php

namespace Revlv\Settings\PaymentTerms;

use Illuminate\Database\Eloquent\Model;

class PaymentTermEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payment_terms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

}
