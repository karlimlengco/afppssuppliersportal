<?php

namespace Revlv\Procurements\Items;

use Illuminate\Database\Eloquent\Model;

class ItemEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'unit_purchase_request_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            'upr_id',
            'item_description',
            'quantity',
            'unit_measurement',
            'unit_price',
            'total_amount',
            'upr_number',
            'afpps_ref_number',
            'prepared_by',
            'date_prepared'
    ];

}
