<?php

namespace Revlv\Biddings\FinalBidDocs;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class FinalBidDocsEloquent extends Model implements  AuditableContract
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
        'action',
        'update_remarks',
        'ref_number',
        'transaction_date',
        'remarks',
        'days',
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
