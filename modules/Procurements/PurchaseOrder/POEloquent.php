<?php

namespace Revlv\Procurements\PurchaseOrder;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;


class POEloquent extends Model implements  AuditableContract
{
    use Auditable;

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'purchase_date',

        'funding_released_date',
        'funding_received_date',
        'service_type',

        'mfo_received_date',
        'mfo_released_date',

        'award_accepted_date',
        'coa_approved_date',
        'update_remarks',
        'delivery_terms',

        'requestor_signatory',
        'accounting_signatory',
        'approver_signatory',
        'coa_name_signatory',
    ];

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
            if($model->id == null)
            {
              $model->id = (string) \Uuid::generate();
            }
        });
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'rfq_id',
        'upr_id',
        'rfq_number',
        'upr_number',
        'purchase_date',
        'bid_amount',
        'service_type',
        'payment_term',
        'delivery_terms',
        'delivery_date',
        'prepared_by',

        'funding_released_date',
        'funding_received_date',
        'type',
        'funding_remarks',
        'update_remarks',

        'mfo_received_date',
        'mfo_released_date',
        'mfo_remarks',

        'signatory_id',
        'status',
        'po_number',
        'received_by',
        'award_accepted_date',
        'requestor_id',
        'accounting_id',
        'approver_id',
        'coa_approved_date',
        'coa_approved',
        'coa_signatory',
        'coa_file',

        'remarks',
        'days',
        'funding_days',
        'mfo_days',
        'coa_days',
        'coa_remarks',

        'action',
        'funding_action',
        'mfo_action',
        'coa_action',

        'requestor_signatory',
        'accounting_signatory',
        'approver_signatory',
        'coa_name_signatory',
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
     * [rfq description]
     *
     * @return [type] [description]
     */
    public function rfq()
    {
        return $this->belongsTo('\Revlv\Procurements\BlankRequestForQuotation\BlankRFQEloquent', 'rfq_id');
    }

    /**
     * [terms description]
     *
     * @return [type] [description]
     */
    public function terms()
    {
        return $this->belongsTo('\Revlv\Settings\PaymentTerms\PaymentTermEloquent', 'payment_term');
    }

    /**
     * [ntp description]
     *
     * @return [type] [description]
     */
    public function ntp()
    {
        return $this->hasOne('\Revlv\Procurements\NoticeToProceed\NTPEloquent', 'po_id');
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
        return $this->hasMany('\Revlv\Procurements\PurchaseOrder\Items\ItemEloquent', 'order_id')->orderBy('created_at');
    }

    /**
     * [delivery description]
     *
     * @return [type] [description]
     */
    public function delivery()
    {
        return $this->hasOne('\Revlv\Procurements\DeliveryOrder\DeliveryOrderEloquent', 'po_id');
    }

    /**
     * [signatories description]
     *
     * @return [type] [description]
     */
    public function signatories()
    {
        return $this->hasOne('\Revlv\Settings\Signatories\SignatoryEloquent', 'id', 'signatory_id');
    }

    /**
     * [coa_signatories description]
     *
     * @return [type] [description]
     */
    public function coa_signatories()
    {
        return $this->hasOne('\Revlv\Settings\Signatories\SignatoryEloquent', 'id', 'coa_signatory');
    }

    /**
     * [requestor description]
     *
     * @return [type] [description]
     */
    public function requestor()
    {
        return $this->hasOne('\Revlv\Settings\Signatories\SignatoryEloquent', 'id', 'requestor_id');
    }

    /**
     * [accounting description]
     *
     * @return [type] [description]
     */
    public function accounting()
    {
        return $this->hasOne('\Revlv\Settings\Signatories\SignatoryEloquent', 'id', 'accounting_id');
    }

    /**
     * [approver description]
     *
     * @return [type] [description]
     */
    public function approver()
    {
        return $this->hasOne('\Revlv\Settings\Signatories\SignatoryEloquent', 'id', 'approver_id');
    }
}
