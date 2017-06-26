<?php

namespace Revlv\Procurements\NoticeOfAward;

use Illuminate\Database\Eloquent\Model;

class NOAEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notice_of_awards';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rfq_id',
        'upr_id',
        'rfq_number',
        'upr_number',
        'signatory_id',
        'canvass_id',
        'proponent_id',
        'awarded_by',
        'awarded_date',
        'remarks',
    ];

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
     * [canvass description]
     *
     * @return [type] [description]
     */
    public function canvass()
    {
        return $this->belongsTo('\Revlv\Procurements\Canvassing\CanvassingEloquent', 'canvass_id');
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
