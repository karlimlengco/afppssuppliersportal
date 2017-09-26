<?php

namespace Revlv\Procurements\BlankRequestForQuotation;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class BlankRFQEloquent extends Model implements  AuditableContract
{

    use Auditable;

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'deadline',
        'opening_time',
        'update_remarks',
        'remarks',
        'transaction_date',
        'signatory_chief',
        // 'completed_at',
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

    protected $dates = [
        'created_at',
        'completed_at',
        'updated_at',
        'transaction_date',
        'awarded_date',
        'award_accepted_date',
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'request_for_quotations';

    protected $with  = ['proponents','invitations'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'upr_id',

        'upr_number',
        'rfq_number',
        'chief',
        'signatory_chief',

        'deadline',
        'opening_time',
        'transaction_date',
        'update_remarks',

        'status',
        'remarks',
        'action',
        'processed_by',

        'awarded_to',
        'awarded_date',
        'completed_at',
        'is_award_accepted',
        'days',
        'award_accepted_date',

        'close_days',
        'close_remarks',
        'close_action',
    ];

    /**
     * [proponents description]
     *
     * @return [type] [description]
     */
    public function proponents()
    {
         return $this->hasMany('\Revlv\Procurements\RFQProponents\RFQProponentEloquent', 'rfq_id')
         ->orderByRaw( "CAST(bid_amount as UNSIGNED)");
    }

    /**
     * [processor description]
     *
     * @return [type] [description]
     */
    public function processor()
    {
        return $this->belongsTo('\App\User', 'processed_by');
    }

    /**
     * [noa description]
     *
     * @return [type] [description]
     */
    public function noa()
    {
        return $this->hasOne('\Revlv\Procurements\NoticeOfAward\NOAEloquent', 'rfq_id');
    }

    /**
     * [ntp description]
     *
     * @return [type] [description]
     */
    public function ntp()
    {
        return $this->hasOne('\Revlv\Procurements\NoticeToProceed\NTPEloquent',  'rfq_id');
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
     * [diir description]
     *
     * @return [type] [description]
     */
    public function diir()
    {
        return $this->hasOne('\Revlv\Procurements\DeliveryInspection\DeliveryInspectionEloquent',  'rfq_id');
    }

    /**
     * [chieftain description]
     *
     * @return [type] [description]
     */
    public function chieftain()
    {
        return $this->belongsTo('\Revlv\Settings\Signatories\SignatoryEloquent', 'chief');
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
     * [canvassing description]
     *
     * @return [type] [description]
     */
    public function canvassing()
    {
        return $this->hasOne('\Revlv\Procurements\Canvassing\CanvassingEloquent', 'rfq_id');
    }

    /**
     * [po description]
     *
     * @return [type] [description]
     */
    public function po()
    {
        return $this->hasOne('\Revlv\Procurements\PurchaseOrder\POEloquent', 'rfq_id');
    }

    /**
     * [invitations description]
     *
     * @return [type] [description]
     */
    public function invitations()
    {
        return $this->hasOne('\Revlv\Procurements\InvitationToSubmitQuotation\Quotations\QuotationEloquent',  'rfq_id');
    }

    /**
     * [items description]
     *
     * @return [type] [description]
     */
    public function items()
    {
        return $this->hasMany('\Revlv\Procurements\Items\ItemEloquent', 'rfq_id');
    }

    /**
     * [philgeps description]
     *
     * @return [type] [description]
     */
    public function philgeps()
    {
        return $this->hasOne('\Revlv\Procurements\PhilGepsPosting\PhilGepsPostingEloquent', 'rfq_id');
    }

}
