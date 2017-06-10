<?php

namespace Revlv\Settings\ModeOfProcurements;

use Illuminate\Database\Eloquent\Model;

class ModeOfProcurementEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mode_of_procurements';

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
