<?php

namespace Revlv\Biddings\PublicNoa;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class PublicNOAEloquent extends Model implements  AuditableContract
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
    protected $table = 'public_bidding_noa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'rfb_id',
       'upr_id',
       'supplier_id',
       'upr_number',
       'rfb_number',

       'received_noa',
       'received_by',

       'approved_noa',

       'supplier_received_noa',
       'supplier_received_by',
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
     * [rfb description]
     *
     * @return [type] [description]
     */
    public function rfb()
    {
        return $this->belongsTo('\Revlv\Biddings\RequestForBid\RequestForBidEloquent', 'rfb_id');
    }

    /**
     * [supplier description]
     *
     * @return [type] [description]
     */
    public function supplier()
    {
        return $this->belongsTo('\Revlv\Settings\Suppliers\SupplierEloquent', 'supplier_id');
    }

    /**
     * [receiver description]
     *
     * @return [type] [description]
     */
    public function receiver()
    {
        return $this->belongsTo('\App\User', 'received_by');
    }

}
