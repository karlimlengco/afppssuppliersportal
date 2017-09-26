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

    public $incrementing = false;

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = (string) \Uuid::generate();
        });
    }

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
