<?php

namespace Revlv\Procurements\Canvassing;

use Illuminate\Database\Eloquent\Model;

class CanvassingEloquent extends Model
{

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
        'canvass_time',
        'open_by',
        'upr_number',
        'order_time',
        'signatory_id',
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
     * [upr description]
     *
     * @return [type] [description]
     */
    public function upr()
    {
        return $this->belongsTo('\Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestEloquent', 'upr_id');
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
