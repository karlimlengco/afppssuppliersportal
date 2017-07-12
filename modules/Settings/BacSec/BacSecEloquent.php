<?php

namespace Revlv\Settings\BacSec;

use Illuminate\Database\Eloquent\Model;

class BacSecEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bacsec';

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
