<?php

namespace Revlv\Settings\ProcurementCenters;

use Illuminate\Database\Eloquent\Model;

class ProcurementCenterEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'procurement_centers';

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
        'programs',
        'short_code',
        'address',
    ];

}
