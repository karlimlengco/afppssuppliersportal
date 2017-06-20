<?php

namespace Revlv\Procurements\DeliveryOrder;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrderEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'delivery_orders';

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
        'po_id',
        'delivery_date',
        'delivery_number',
        'transaction_date',
        'expected_date',
        'prepared_by',
        'created_by',
        'status',
        'date_completed',
        'notes',
        'inspection_status',
        'date_delivered_to_coa',
        'delivered_to_coa_by',
    ];

    /**
     * [creator description]
     *
     * @return [type] [description]
     */
    public function creator()
    {
        return $this->belongsTo('\App\User', 'created_by');
    }

    /**
     * [deliveryMan description]
     *
     * @return [type] [description]
     */
    public function deliveryMan()
    {
        return $this->belongsTo('\App\User', 'delivered_to_coa_by');
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
        return $this->hasMany('\Revlv\Procurements\DeliveryOrder\Items\ItemEloquent', 'order_id');
    }
}