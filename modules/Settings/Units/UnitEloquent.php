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
        'pcco_id',
        'coa_address',
        'description',
    ];


    /**
     * [centers description]
     *
     * @return [type] [description]
     */
    public function centers()
    {
        return $this->belongsTo('\Revlv\Settings\ProcurementCenters\ProcurementCenterEloquent', 'pcco_id');
    }

}
