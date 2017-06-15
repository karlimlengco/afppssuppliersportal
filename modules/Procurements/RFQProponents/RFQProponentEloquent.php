<?php

namespace Revlv\Procurements\RFQProponents;

use Illuminate\Database\Eloquent\Model;

class RFQProponentEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rfq_proponents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rfq_id',
        'proponents',
        'note',
        'date_processed',
        'prepared_by',
    ];


}
