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
        'prepared_by',
        'received_by',
        'award_accepted_date',
        'is_awarded',
        'awarded_date',
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
}
