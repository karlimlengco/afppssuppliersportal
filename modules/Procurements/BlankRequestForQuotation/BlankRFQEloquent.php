<?php

namespace Revlv\Procurements\BlankRequestForQuotation;

use Illuminate\Database\Eloquent\Model;

class BlankRFQEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'request_for_quotations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'upr_id',
        'upr_number',
        'rfq_number',
        'deadline',
        'status',
        'opening_time',
        'transaction_date'
    ];

}
