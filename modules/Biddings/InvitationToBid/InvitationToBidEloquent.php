<?php

namespace Revlv\Biddings\InvitationToBid;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class InvitationToBidEloquent extends Model implements  AuditableContract
{

    use Auditable;

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = ['approved_date', 'update_remarks'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invitation_to_bid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'upr_id',
        'action',
        'upr_number',
        'ref_number',
        'update_remarks',
        'transaction_date',
        'approved_date',
        'approved_by',
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
        return $this->belongsTo('\App\User', 'approved_by');
    }

}
