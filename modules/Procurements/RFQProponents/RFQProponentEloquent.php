<?php

namespace Revlv\Procurements\RFQProponents;

use Illuminate\Database\Eloquent\Model;

class RFQProponentEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rfq_proponents';
    protected $with  = ['supplier', 'winners'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rfq_id',
        'proponents',
        'note',
        'date_processed',
        'status',
        'prepared_by',
        'received_by',
        'remarks',
        'award_accepted_date',
        'is_awarded',
        'awarded_date',
        'signatory_id',
        'bid_amount',
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
     * [supplier description]
     *
     * @return [type] [description]
     */
    public function supplier()
    {
        return $this->belongsTo('\Revlv\Settings\Suppliers\SupplierEloquent', 'proponents');
    }

    /**
     * [attachments description]
     *
     * @return [type] [description]
     */
    public function attachments()
    {
         return $this->hasMany('\Revlv\Procurements\ProponentAttachments\ProponentAttachmentEloquent', 'proponent_id');
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
     * [winners description]
     *
     * @return [type] [description]
     */
    public function winners()
    {
        return $this->hasOne('\Revlv\Procurements\NoticeOfAward\NOAEloquent', 'canvass_id');
    }
}
