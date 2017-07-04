<?php

namespace Revlv\Procurements\DeliveryInspection;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class DeliveryInspectionEloquent extends Model implements  AuditableContract
{
    use Auditable;

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'start_date',
        'closed_date',
        'update_remarks',
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'delivery_inspection';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dr_id',
        'upr_id',
        'rfq_id',
        'update_remarks',
        'rfq_number',
        'upr_number',
        'delivery_number',
        'inspection_number',
        'start_date',
        'status',
        'closed_date',
        'started_by',
        'closed_by',

        'received_by',
        'approved_by',
        'issued_by',
        'requested_by',
    ];

    /**
     * [receiver description]
     *
     * @return [type] [description]
     */
    public function receiver()
    {
        return $this->belongsTo('\Revlv\Procurements\Canvassing\Signatories\SignatoryEloquent', 'received_by');
    }

    /**
     * [approver description]
     *
     * @return [type] [description]
     */
    public function approver()
    {
        return $this->belongsTo('\Revlv\Procurements\Canvassing\Signatories\SignatoryEloquent', 'approved_by');
    }

    /**
     * [issuer description]
     *
     * @return [type] [description]
     */
    public function issuer()
    {
        return $this->belongsTo('\Revlv\Procurements\Canvassing\Signatories\SignatoryEloquent', 'issued_by');
    }


    /**
     * [requestor description]
     *
     * @return [type] [description]
     */
    public function requestor()
    {
        return $this->belongsTo('\Revlv\Procurements\Canvassing\Signatories\SignatoryEloquent', 'requested_by');
    }

    /**
     * [started description]
     *
     * @return [type] [description]
     */
    public function started()
    {
        return $this->belongsTo('\App\User', 'started_by');
    }

    /**
     * [closed description]
     *
     * @return [type] [description]
     */
    public function closed()
    {
        return $this->belongsTo('\App\User', 'closed_by');
    }

    /**
     * [items description]
     *
     * @return [type] [description]
     */
    public function issues()
    {
        return $this->hasMany('\Revlv\Procurements\DeliveryInspection\Issues\IssueEloquent', 'inspection_id');
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
     * [rfq description]
     *
     * @return [type] [description]
     */
    public function rfq()
    {
        return $this->belongsTo('\Revlv\Procurements\BlankRequestForQuotation\BlankRFQEloquent', 'rfq_id');
    }

    /**
     * [delivery description]
     *
     * @return [type] [description]
     */
    public function delivery()
    {
        return $this->belongsTo('\Revlv\Procurements\DeliveryOrder\DeliveryOrderEloquent', 'dr_id');
    }
}
