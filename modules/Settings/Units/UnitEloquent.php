<?php

namespace Revlv\Settings\Units;

use Illuminate\Database\Eloquent\Model;

class UnitEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'units';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'pcco_id',
        'coa_address',
        'description',
    ];

}
