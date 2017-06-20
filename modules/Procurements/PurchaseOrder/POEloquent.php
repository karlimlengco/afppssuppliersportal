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
        'pcco_has_issue',
        'pcco_released_date',
        'pcco_received_date',
        'pcco_remarks',
        'mfo_has_issue',
        'mfo_received_date',
        'mfo_released_date',
        'mfo_remarks',
        'status',
        'received_by',
        'award_accepted_date',

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

    /**
     * [items description]
     *
     * @return [type] [description]
     */
    public function items()
    {
        return $this->hasMany('\Revlv\Procurements\PurchaseOrder\Items\ItemEloquent', 'order_id');
    }

    /**
     * [delivery description]
     *
     * @return [type] [description]
     */
    public function delivery()
    {
        return $this->hasOne('\Revlv\Procurements\DeliveryOrder\DeliveryOrderEloquent', 'po_id');
    }
}
