<?php

namespace Revlv\Biddings\BidDocs;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class BidDocsEloquent extends Model implements  AuditableContract
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
    protected $table = 'bid_docs_issuance';

    protected $casts = [
        'id' => 'string'
    ];

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
        'proponent_id',
        'proponent_name',
        'processed_by',
        'remarks',
        'update_remarks',
        'days',
        'is_lcb',
        'is_scb',
        'status',
        'bid_amount',
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

    /**
     * [supplier description]
     *
     * @return [type] [description]
     */
    public function supplier()
    {
        return $this->belongsTo('\Revlv\Settings\Suppliers\SupplierEloquent', 'proponent_id');
    }

}
