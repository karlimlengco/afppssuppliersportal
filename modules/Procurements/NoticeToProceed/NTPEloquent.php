<?php

namespace Revlv\Procurements\NoticeToProceed;

use Illuminate\Database\Eloquent\Model;

class NTPEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notice_to_proceed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'po_id',
        'rfq_id',
        'upr_id',
        'rfq_number',
        'upr_number',
        'signatory_id',
        'proponent_id',
        'prepared_by',
        'prepared_date',
        'status',
        'remarks',
        'file',

        'received_by',
        'award_accepted_date',
        'accepted_date',
    ];


    /**
     * [users description]
     *
     * @return [type] [description]
     */
    public function users()
    {
        return $this->belongsTo('\App\User', 'prepared_by');
    }

    /**
     * [signatory description]
     *
     * @return [type] [description]
     */
    public function signatory()
    {
        return $this->hasOne('\Revlv\Settings\Signatories\SignatoryEloquent', 'id', 'signatory_id');
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
     * [upr description]
     *
     * @return [type] [description]
     */
    public function upr()
    {
        return $this->belongsTo('\Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestEloquent', 'upr_id');
    }
    /**
     * [po description]
     *
     * @return [type] [description]
     */
    public function po()
    {
        return $this->belongsTo('\Revlv\Procurements\PurchaseOrder\POEloquent', 'po_id');
    }

    /**
     * [winners description]
     *
     * @return [type] [description]
     */
    public function winner()
    {
        return $this->belongsTo('\Revlv\Procurements\RFQProponents\RFQProponentEloquent', 'proponent_id');
    }

}