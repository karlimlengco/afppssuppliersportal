<?php

namespace Revlv\Settings\Banks;

use Illuminate\Database\Eloquent\Model;

class BankEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'banks';

    protected $casts = [
        'id' => 'string'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'code',
        'description',
    ];

}
