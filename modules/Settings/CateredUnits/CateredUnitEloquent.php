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

    protected $casts = [
        'id' => 'string'
    ];

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

    /**
     * [attachments description]
     *
     * @return [type] [description]
     */
    public function attachments()
    {
         return $this->hasMany('\Revlv\Settings\CateredUnits\Attachments\AttachmentEloquent', 'unit_id');
    }

    /**
     * [unit description]
     *
     * @return [type] [description]
     */
    public function centers()
    {
        return $this->belongsTo('\Revlv\Settings\ProcurementCenters\ProcurementCenterEloquent', 'pcco_id');
    }

}
