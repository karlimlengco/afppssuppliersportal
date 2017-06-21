<?php

namespace Revlv\Procurements\InvitationToSubmitQuotation;

use Illuminate\Database\Eloquent\Model;

class ISPQEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invitation_for_quotation';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'signatory_id',
        'venue',
        'transaction_date',
        'prepared_by',
    ];

    /**
     * [ispq_id description]
     *
     * @return [type] [description]
     */
    public function quotations()
    {
        return $this->hasMany('\Revlv\Procurements\InvitationToSubmitQuotation\Quotations\QuotationEloquent', 'ispq_id');
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
     * [signatories description]
     *
     * @return [type] [description]
     */
    public function signatories()
    {
        return $this->hasOne('\Revlv\Settings\Signatories\SignatoryEloquent', 'id', 'signatory_id');
    }
}
