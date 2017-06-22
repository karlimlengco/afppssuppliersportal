<?php

namespace Revlv\Settings\CateredUnits;

use Illuminate\Database\Eloquent\Model;

class CateredUnitEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'catered_units';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pcco_id',
        'short_code',
        'description',
        'coa_address',
        'coa_address_2',
    ];

}
