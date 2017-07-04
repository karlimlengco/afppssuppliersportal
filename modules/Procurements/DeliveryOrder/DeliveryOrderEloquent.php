<?php

namespace Revlv\Procurements\DeliveryOrder;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class DeliveryOrderEloquent extends Model implements  AuditableContract
{
    use Auditable;

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'delivery_date',
        'transaction_date',
        'expected_date',
        'date_completed',
        'date_delivered_to_coa',
        'update_remarks',
    ];


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
        'signatory_id',
        'date_completed',
        'notes',
        'update_remarks',
        'inspection_status',
        'date_delivered_to_coa',
        'delivered_to_coa_by',
        'received_by',
    ];


    /**
     * [upr description]
     *
     * @return [type] [description]
     */
    public function upr()
    {
        return $this->belongsTo('\Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestEloquent', 'upr_id');
    }


    /**
     * [po description]
     *
     * @return [type] [description]
     */
    public function po()
    {
        return $this->belongsTo('\Revlv\Procurements\PurchaseOrder\POEloquent', 'po_id');
    }


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
     * [receiver description]
     *
     * @return [type] [description]
     */
    public function receiver()
    {
        return $this->belongsTo('\App\User', 'received_by');
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

    /**
     * [signatories description]
     *
     * @return [type] [description]
     */
    public function signatory()
    {
        return $this->belongsTo('\Revlv\Settings\Signatories\SignatoryEloquent', 'signatory_id');
    }

    /**
     * [inspections description]
     *
     * @return [type] [description]
     */
    public function inspections()
    {
        return $this->hasOne('\Revlv\Procurements\InspectionAndAcceptance\InspectionAndAcceptanceEloquent', 'dr_id');
    }

    /**
     * [diir description]
     *
     * @return [type] [description]
     */
    public function diir()
    {
        return $this->hasOne('\Revlv\Procurements\DeliveryInspection\DeliveryInspectionEloquent', 'dr_id');
    }
}
