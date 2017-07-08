<?php

namespace Revlv\Settings\ProcurementTypes;

use Illuminate\Database\Eloquent\Model;

class ProcurementTypeEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'procurement_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'description',
    ];

}
