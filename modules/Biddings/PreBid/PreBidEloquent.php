<?php

namespace Revlv\Biddings\PreBid;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class PreBidEloquent extends Model implements  AuditableContract
{

    use Auditable;

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'update_remarks',
        'action',
        'transaction_date',
        'is_scb_issue',
        'is_resched',
        'bid_opening_date',
        'resched_date',
        'resched_remarks',
        'remarks',
        'sbb_date',
        'failed_remarks',
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pre_bid_conferences';

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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'upr_id',
        'update_remarks',
        'action',
        'upr_number',
        'ref_number',
        'transaction_date',
        'is_scb_issue',
        'is_resched',
        'sbb_date',
        'bid_opening_date',
        'failed_remarks',
        'resched_date',
        'resched_remarks',
        'remarks',
        'days',
        'processed_by',
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
     * [user description]
     *
     * @return [type] [description]
     */
    public function user()
    {
        return $this->belongsTo('\App\User', 'processed_by');
    }

}
