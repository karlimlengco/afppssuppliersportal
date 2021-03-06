<?php

namespace Revlv\Procurements\Canvassing;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class CanvassingEloquent extends Model implements  AuditableContract
{

    use Auditable;

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'canvass_date',
        'adjourned_time',
        'closebox_time',
        'canvass_time',
        'update_remarks',
        'order_time',

        'chief_signatory',
        'presiding_signatory',
        'unit_head_signatory',
        'mfo_signatory',
        'legal_signatory',
        'secretary_signatory',
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
    protected $table = 'canvassing';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'canvass_date',
        'adjourned_time',
        'closebox_time',
        'rfq_id',
        'upr_id',
        'rfq_number',
        'action',
        'canvass_time',
        'open_by',
        'update_remarks',
        'upr_number',
        'order_time',
        'days',
        'signatory_id',
        'remarks',
        'resolution',
        'is_failed',
        'failed_remarks',
        'date_failed',
        'chief',
        'presiding_officer',
        'other_attendees',

        'chief_signatory',
        'presiding_signatory',
        'unit_head_signatory',
        'mfo_signatory',
        'legal_signatory',
        'secretary_signatory',

        'unit_head',
        'mfo',
        'legal',
        'secretary',


        'unit_head_attendance',
        'mfo_attendance',
        'legal_attendance',
        'secretary_attendance',
        'chief_attendance',
        'cop',
        'rop',
    ];

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
     * [officer description]
     *
     * @return [type] [description]
     */
    public function officer()
    {
        return $this->belongsTo('\Revlv\Settings\Signatories\SignatoryEloquent', 'presiding_officer');
    }

    /**
     * [chieftain description]
     *
     * @return [type] [description]
     */
    public function chieftain()
    {
        return $this->belongsTo('\Revlv\Settings\Signatories\SignatoryEloquent', 'chief');
    }

    /**
     * [unit_head_name description]
     *
     * @return [type] [description]
     */
    public function unit_head_name()
    {
        return $this->belongsTo('\Revlv\Settings\Signatories\SignatoryEloquent', 'unit_head');
    }

    /**
     * [mfo_name description]
     *
     * @return [type] [description]
     */
    public function mfo_name()
    {
        return $this->belongsTo('\Revlv\Settings\Signatories\SignatoryEloquent', 'mfo');
    }

    /**
     * [legal_name description]
     *
     * @return [type] [description]
     */
    public function legal_name()
    {
        return $this->belongsTo('\Revlv\Settings\Signatories\SignatoryEloquent', 'legal');
    }

    /**
     * [secretary_name description]
     *
     * @return [type] [description]
     */
    public function secretary_name()
    {
        return $this->belongsTo('\Revlv\Settings\Signatories\SignatoryEloquent', 'secretary');
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
     * [winners description]
     *
     * @return [type] [description]
     */
    public function winners()
    {
        return $this->hasOne('\Revlv\Procurements\NoticeOfAward\NOAEloquent', 'canvass_id');
    }

    // /**
    //  * [signatories description]
    //  *
    //  * @return [type] [description]
    //  */
    // public function signatories()
    // {
    //     return $this->hasOne('\Revlv\Settings\Signatories\SignatoryEloquent', 'id', 'signatory_id');
    // }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function signatories()
    {
        return $this->hasMany('\Revlv\Procurements\Canvassing\Signatories\SignatoryEloquent', 'canvass_id');
        // return $this->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id')->withTimestamps();
    }

    /**
     * [opens description]
     *
     * @return [type] [description]
     */
    public function opens()
    {
        return $this->belongsTo('\App\User', 'open_by');
    }
}
