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
    protected $auditInclude = [];

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
