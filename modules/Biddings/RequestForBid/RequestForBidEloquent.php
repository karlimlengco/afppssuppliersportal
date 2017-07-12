<?php

namespace Revlv\Biddings\RequestForBid;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class RequestForBidEloquent extends Model implements  AuditableContract
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
    protected $table = 'request_for_bidding';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'upr_id',
        'upr_number',
        'rfb_number',

        'bac_id',

        'transaction_date',
        'released_date',

        'received_date',
        'received_by',

        'status',
        'remarks',
        'update_remarks',
        'processed_by',

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
        return $this->hasOne('\Revlv\Biddings\PublicNoa\PublicNOAEloquent', 'rfb_id');
    }

    /**
     * [bacsec description]
     *
     * @return [type] [description]
     */
    public function bacsec()
    {
        return $this->belongsTo('\Revlv\Settings\BacSec\BacSecEloquent', 'bac_id');
    }
}
