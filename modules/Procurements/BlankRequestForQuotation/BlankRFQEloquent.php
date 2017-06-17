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

    protected $with  = 'proponents';

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
        'awarded_to',
        'awarded_date',
        'transaction_date'
    ];

    /**
     * [proponents description]
     *
     * @return [type] [description]
     */
    public function proponents()
    {
         return $this->hasMany('\Revlv\Procurements\RFQProponents\RFQProponentEloquent', 'rfq_id');
    }

}
