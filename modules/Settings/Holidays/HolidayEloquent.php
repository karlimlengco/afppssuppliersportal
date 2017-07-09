<?php

namespace Revlv\Settings\Holidays;

use Illuminate\Database\Eloquent\Model;

class HolidayEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'holidays';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'holiday_date',
    ];

}