<?php

namespace Revlv\Procurements\NoticeOfAward;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class NOAEloquent extends Model implements  AuditableContract
{

    use Auditable;

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'awarded_date',
        'remarks',
        'update_remarks',
        'award_accepted_date',
        'accepted_date',
        'signatory',
        'perfomance_bond',
        'amount',
        'notes',
    ];

    protected $casts = [
        'id' => 'string'
    ];

    public $incrementing = false;

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            if($model->id == null)
            {
              $model->id = (string) \Uuid::generate();
            }
        });
    }


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notice_of_awards';

    // protected $with = 'winner';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'rfq_id',
        'upr_id',
        'rfq_number',
        'upr_number',
        'signatory_id',
        'canvass_id',
        'proponent_id',
        'awarded_by',
        'resolution',
        'signatory',
        'awarded_date',
        'update_remarks',
        'remarks',
        'received_by',
        'file',
        'seconded_by',
        'status',
        'award_accepted_date',
        'account_type',
        'accepted_date',

        'days',

        'approved_remarks',
        'approved_days',

        'received_remarks',
        'received_days',

        'action',
        'approved_action',
        'received_action',
        'perfomance_bond',
        'amount',
        'notes',
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
     * [signatory description]
     *
     * @return [type] [description]
     */
    public function awarder()
    {
        return $this->hasOne('\Revlv\Settings\Signatories\SignatoryEloquent', 'id', 'awarded_by');
    }

    /**
     * [seconder description]
     *
     * @return [type] [description]
     */
    public function seconder()
    {
        return $this->hasOne('\Revlv\Settings\Signatories\SignatoryEloquent', 'id', 'seconded_by');
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
     * [canvass description]
     *
     * @return [type] [description]
     */
    public function pq()
    {
        return $this->belongsTo('\Revlv\Biddings\PostQualification\PostQualificationEloquent', 'canvass_id');
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

    public function biddingWinner()
    {
        return $this->belongsTo('\Revlv\Biddings\BidDocs\BidDocsEloquent', 'proponent_id');
    }

}
