<?php

namespace Revlv\Procurements\InvitationToSubmitQuotation\Quotations;

use Illuminate\Database\Eloquent\Model;

class QuotationEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ispq_quotations';
    protected $with =   'ispq';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ispq_id',
        'rfq_id',
        'upr_id',
        'description',
        'total_amount',
        'canvassing_date',
        'canvassing_time',
        'upr_number',
        'rfq_number',
    ];

    /**
     * [ispq description]
     *
     * @return [type] [description]
     */
    public function ispq()
    {
        return $this->belongsTo('\Revlv\Procurements\InvitationToSubmitQuotation\ISPQEloquent', 'ispq_id');
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
     * [rfq_id description]
     *
     * @return [type] [description]
     */
    public function rfq()
    {
        return $this->belongsTo('\Revlv\Procurements\BlankRequestForQuotation\BlankRFQEloquent', 'rfq_id');
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

}
