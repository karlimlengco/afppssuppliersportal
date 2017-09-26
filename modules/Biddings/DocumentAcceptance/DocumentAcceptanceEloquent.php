<?php

namespace Revlv\Biddings\DocumentAcceptance;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class DocumentAcceptanceEloquent extends Model implements  AuditableContract
{

    use Auditable;

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'upr_id',
        'bac_id',
        'upr_number',
        'ref_number',
        'transaction_date',
        'pre_proc_date',
        'approved_date',
        'return_date',
        'return_remarks',
        'remarks',
        'action',
        'update_remarks',
        'days',
        'processed_by',
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
    protected $table = 'document_acceptance';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'upr_id',
        'id',
        'bac_id',
        'upr_number',
        'ref_number',
        'transaction_date',
        'pre_proc_date',
        'approved_date',
        'return_date',
        'return_remarks',
        'remarks',
        'action',
        'update_remarks',
        'days',
        'processed_by',
    ];

    /**
     * [upr description]
     *
     * @return [type] [description]
     */
    public function bacsec()
    {
        return $this->belongsTo('\Revlv\Settings\BacSec\BacSecEloquent', 'bac_id');
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
     * [user description]
     *
     * @return [type] [description]
     */
    public function user()
    {
        return $this->belongsTo('\App\User', 'processed_by');
    }

}
