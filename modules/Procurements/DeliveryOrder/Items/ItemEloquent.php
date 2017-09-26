<?php

namespace Revlv\Procurements\DeliveryOrder\Items;

use Illuminate\Database\Eloquent\Model;

class ItemEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'delivery_order_items';

    protected $casts = [
        'id' => 'string'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'order_id',
        'description',
        'quantity',
        'unit',
        'price_unit',
        'total_amount',
        'received_quantity',
    ];

}
