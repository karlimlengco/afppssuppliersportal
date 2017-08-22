<?php

namespace Revlv\Procurements\PurchaseOrder\Items;

use Illuminate\Database\Eloquent\Model;

class ItemEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_order_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'description',
        'quantity',
        'unit',
        'price_unit',
        'type',
        'total_amount',
    ];

}
