<?php

namespace Revlv\Biddings\PreProc;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class PreProcEloquent extends Model implements  AuditableContract
{

    use Auditable;

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'upr_id',
        'upr_number',
        'ref_number',
        'pre_proc_date',
        'resched_date',
        'resched_remarks',
        'remarks',
        'action',
        'update_remarks',
        'days',
        'processed_by',
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pre_proc';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'upr_id',
        'upr_number',
        'ref_number',
        'pre_proc_date',
        'resched_date',
        'resched_remarks',
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
