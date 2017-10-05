<?php

namespace Revlv\Biddings\BidOpening;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class BidOpeningEloquent extends Model implements  AuditableContract
{

    use Auditable;

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'action',
        'transaction_date',
        'update_remarks',
        'closing_date',
        'is_completed',
        'days',
        'remarks',
    ];

    protected $casts = [
        'id' => 'string'
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bid_opening';

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
        'upr_number',
        'action',
        'ref_number',
        'transaction_date',
        'update_remarks',
        'closing_date',
        'is_completed',
        'days',
        'remarks',
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
