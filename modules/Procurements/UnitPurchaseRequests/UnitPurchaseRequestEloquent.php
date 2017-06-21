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

    protected $with =   ['unit','centers'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'place_of_delivery',
        'status',
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
        'date_processed',
        'units',
        'prepared_by',
    ];

    /**
     * [philgeps description]
     *
     * @return [type] [description]
     */
    public function philgeps()
    {
        return $this->hasOne('\Revlv\Procurements\PhilGepsPosting\PhilGepsPostingEloquent', 'upr_id');
    }

    /**
     * [rfq description]
     *
     * @return [type] [description]
     */
    public function rfq()
    {
        return $this->hasOne('\Revlv\Procurements\BlankRequestForQuotation\BlankRFQEloquent', 'upr_id');
    }

    /**
     * [canvassing description]
     *
     * @return [type] [description]
     */
    public function canvassing()
    {
        return $this->hasOne('\Revlv\Procurements\Canvassing\CanvassingEloquent', 'upr_id');
    }

    /**
     * [purchase_order description]
     *
     * @return [type] [description]
     */
    public function purchase_order()
    {
        return $this->hasOne('\Revlv\Procurements\PurchaseOrder\POEloquent', 'upr_id');
    }

    /**
     * [delivery_order description]
     *
     * @return [type] [description]
     */
    public function delivery_order()
    {
        return $this->hasOne('\Revlv\Procurements\DeliveryOrder\DeliveryOrderEloquent', 'upr_id');
    }

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
