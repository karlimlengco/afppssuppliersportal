<?php

namespace Revlv\Settings\Forms\Header;

use Illuminate\Database\Eloquent\Model;

class HeaderEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'form_headers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unit_id',
        'content',
    ];

}
