<?php

namespace Revlv\Settings\Signatories;

use Illuminate\Database\Eloquent\Model;

class SignatoryEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'signatories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'designation',
        'ranks',
    ];

}
