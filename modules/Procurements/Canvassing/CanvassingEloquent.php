<?php

namespace Revlv\Procurements\Canvassing;

use Illuminate\Database\Eloquent\Model;

class CanvassingEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'canvassing';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'canvass_date',
        'adjourned_time',
        'closebox_time',
        'rfq_id',
        'rfq_number',
        'upr_number',
        'order_time',
    ];
}
