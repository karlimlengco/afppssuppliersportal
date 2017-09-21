<?php

namespace Revlv\Settings\Chargeability;

use Illuminate\Database\Eloquent\Model;

class ChargeabilityEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'chargeability';

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
        'name',
        'description',
    ];

}
