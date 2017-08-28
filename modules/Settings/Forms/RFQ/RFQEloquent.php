<?php

namespace Revlv\Settings\Forms\RFQ;

use Illuminate\Database\Eloquent\Model;

class RFQEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'forms_rfq';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pcco_id',
        'content',
    ];

}
