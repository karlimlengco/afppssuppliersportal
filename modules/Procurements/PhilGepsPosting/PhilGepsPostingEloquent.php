<?php

namespace Revlv\Procurements\PhilGepsPosting;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class PhilGepsPostingEloquent extends Model implements  AuditableContract
{

    use Auditable;

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'philgeps_number',
        'philgeps_posting',
        'deadline_rfq',
        'opening_time',
        'transaction_date',
        'update_remarks',
        'remarks',
        'newspaper',
        'status',
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
    protected $table = 'philgeps_posting';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'rfq_id',
        'upr_id',
        'philgeps_number',
        'rfq_number',
        'upr_number',
        'transaction_date',
        'philgeps_posting',
        'status',
        'newspaper',
        'deadline_rfq',
        'update_remarks',
        'remarks',
        'days',
        'action',
        'status',
        'status_remarks',
        'opening_time',
    ];

    /**
     * [attachments description]
     *
     * @return [type] [description]
     */
    public function attachments()
    {
         return $this->hasMany('\Revlv\Procurements\PhilGepsPosting\Attachments\AttachmentEloquent', 'philgeps_id');
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
}
