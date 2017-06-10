<?php

namespace Revlv\Procurements\UnitPurchaseRequests;

use Illuminate\Database\Eloquent\Model;

class UnitPurchaseRequestEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'unit_purchase_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'place_of_delivery',
        'mode_of_procurement',
        'chargability',
        'account_code',
        'fund_validity',
        'terms_of_payment',
        'other_infos',
        'upr_number',
        'purpose',
        'afpps_ref_number',
        'date_prepared',
        'total_amount',
        'prepared_by',
    ];

}
