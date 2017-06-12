<?php

namespace Revlv\Procurements\PhilGepsPosting;

use Illuminate\Database\Eloquent\Model;

class PhilGepsPostingEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'philgeps_posting';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'upr_id',
        'philgeps_number',
        'upr_number',
        'transaction_date',
        'philgeps_posting',
        'deadline_rfq',
        'opening_time',
    ];

}
