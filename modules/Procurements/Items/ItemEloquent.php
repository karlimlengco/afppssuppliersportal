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

    protected $with = 'accounts';

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
            'upr_id',
            'item_description',
            'quantity',
            'unit_measurement',
            'unit_price',
            'total_amount',
            'upr_number',
            'afpps_ref_number',
            'prepared_by',
            'date_prepared',
            'new_account_code'
    ];

    public function accounts()
    {
        return $this->belongsTo('\Revlv\Settings\AccountCodes\AccountCodeEloquent', 'new_account_code');
    }

}
