<?php

namespace Revlv\Procurements\NoticeToProceed;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class NTPEloquent extends Model implements  AuditableContract
{
    use Auditable;

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'prepared_date',
        'remarks',
        'update_remarks',
        'award_accepted_date',
        'accepted_date',
        'signatory',
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
            $model->id = (string) \Uuid::generate();
        });
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notice_to_proceed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'po_id',
        'rfq_id',
        'upr_id',
        'rfq_number',
        'upr_number',
        'signatory_id',
        'proponent_id',
        'prepared_by',
        'prepared_date',
        'signatory',
        'status',
        'remarks',
        'update_remarks',
        'accepted_action',
        'action',
        'file',

        'received_by',
        'award_accepted_date',
        'accepted_date',

        'days',
        'accepted_days',
        'accepted_remarks',
    ];


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
     * [signatory description]
     *
     * @return [type] [description]
     */
    public function signatory()
    {
        return $this->belongsTo('\Revlv\Settings\Signatories\SignatoryEloquent', 'signatory_id');
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
     * [delivery description]
     *
     * @return [type] [description]
     */
    public function delivery()
    {
        return $this->hasOne('\Revlv\Procurements\DeliveryOrder\DeliveryOrderEloquent',  'rfq_id');
    }

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
     * [winners description]
     *
     * @return [type] [description]
     */
    public function winner()
    {
        return $this->belongsTo('\Revlv\Procurements\RFQProponents\RFQProponentEloquent', 'proponent_id');
    }


    public function biddingWinner()
    {
        return $this->belongsTo('\Revlv\Biddings\BidDocs\BidDocsEloquent', 'proponent_id');
    }
}
