<?php

namespace Revlv\Procurements\PurchaseOrder;

use Illuminate\Database\Eloquent\Model;

class POEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rfq_id',
        'upr_id',
        'rfq_number',
        'upr_number',
        'purchase_date',
        'bid_amount',
        'payment_term',
        'prepared_by',
    ];

    /**
     * [terms description]
     *
     * @return [type] [description]
     */
    public function terms()
    {
        return $this->belongsTo('\Revlv\Settings\PaymentTerms\PaymentTermEloquent', 'payment_term');
    }

    /**
     * [users description]
     *
     * @return [type] [description]
     */
    public function users()
    {
        return $this->belongsTo('\App\User', 'prepared_by');
    }
}
