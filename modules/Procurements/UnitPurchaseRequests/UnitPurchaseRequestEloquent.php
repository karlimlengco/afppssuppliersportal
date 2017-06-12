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
        'chargeability',
        'account_code',
        'fund_validity',
        'terms_of_payment',
        'other_infos',
        'upr_number',
        'purpose',
        'afpps_ref_number',
        'date_prepared',
        'total_amount',
        'units',
        'prepared_by',
    ];

    /**
     * [centers description]
     *
     * @return [type] [description]
     */
    public function centers()
    {
        return $this->belongsTo('\Revlv\Settings\ProcurementCenters\ProcurementCenterEloquent', 'place_of_delivery');
    }

    /**
     * [modes description]
     *
     * @return [type] [description]
     */
    public function modes()
    {
        return $this->belongsTo('\Revlv\Settings\ModeOfProcurements\ModeOfProcurementEloquent', 'mode_of_procurement');
    }

    /**
     * [charges description]
     *
     * @return [type] [description]
     */
    public function charges()
    {
        return $this->belongsTo('\Revlv\Settings\Chargeability\ChargeabilityEloquent', 'chargeability');
    }

    /**
     * [unit description]
     *
     * @return [type] [description]
     */
    public function unit()
    {
        return $this->belongsTo('\Revlv\Settings\Units\UnitEloquent', 'units');
    }

    /**
     * [accounts description]
     *
     * @return [type] [description]
     */
    public function accounts()
    {
        return $this->belongsTo('\Revlv\Settings\AccountCodes\AccountCodeEloquent', 'account_code');
    }

    /**
     * [terms description]
     *
     * @return [type] [description]
     */
    public function terms()
    {
        return $this->belongsTo('\Revlv\Settings\PaymentTerms\PaymentTermEloquent', 'terms_of_payment');
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
        return $this->hasMany('\Revlv\Procurements\Items\ItemEloquent', 'upr_id');
    }

}
