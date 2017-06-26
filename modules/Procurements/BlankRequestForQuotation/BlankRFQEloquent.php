<?php

namespace Revlv\Procurements\BlankRequestForQuotation;

use Illuminate\Database\Eloquent\Model;

class BlankRFQEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'request_for_quotations';

    protected $with  = ['proponents','invitations'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'upr_id',

        'upr_number',
        'rfq_number',

        'deadline',
        'opening_time',
        'transaction_date',

        'status',
        'remarks',
        'processed_by',

        'awarded_to',
        'awarded_date',
        'completed_at',
        'is_award_accepted',
        'award_accepted_date',
    ];

    /**
     * [proponents description]
     *
     * @return [type] [description]
     */
    public function proponents()
    {
         return $this->hasMany('\Revlv\Procurements\RFQProponents\RFQProponentEloquent', 'rfq_id');
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
        return $this->hasOne('\Revlv\Procurements\NoticeOfAward\NOAELoquent', 'upr_id');
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
     * [canvassing description]
     *
     * @return [type] [description]
     */
    public function canvassing()
    {
        return $this->hasOne('\Revlv\Procurements\Canvassing\CanvassingEloquent', 'rfq_id');
    }

    /**
     * [po description]
     *
     * @return [type] [description]
     */
    public function po()
    {
        return $this->hasOne('\Revlv\Procurements\PurchaseOrder\POEloquent', 'rfq_id');
    }

    /**
     * [invitations description]
     *
     * @return [type] [description]
     */
    public function invitations()
    {
        return $this->hasOne('\Revlv\Procurements\InvitationToSubmitQuotation\Quotations\QuotationEloquent', 'rfq_id');
    }

    /**
     * [items description]
     *
     * @return [type] [description]
     */
    public function items()
    {
        return $this->hasMany('\Revlv\Procurements\Items\ItemEloquent', 'upr_id');
    }

    /**
     * [philgeps description]
     *
     * @return [type] [description]
     */
    public function philgeps()
    {
        return $this->hasOne('\Revlv\Procurements\PhilGepsPosting\PhilGepsPostingEloquent', 'rfq_id');
    }

}
