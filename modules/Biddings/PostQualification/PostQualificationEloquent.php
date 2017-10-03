<?php

namespace Revlv\Biddings\PostQualification;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class PostQualificationEloquent extends Model implements  AuditableContract
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
        'approved_date',
        'resched_date',
        'resched_remarks',
        'remarks',
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'post_qualification';

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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'upr_id',
        'upr_number',
        'update_remarks',
        'action',
        'ref_number',
        'transaction_date',
        'approved_date',
        'resched_date',
        'resched_remarks',
        'remarks',
        'days',
        'processed_by',
        'date_failed',
        'disqualification_date',
        'disqualification_remarks',
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
