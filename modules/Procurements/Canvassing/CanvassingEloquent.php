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
    ];

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
