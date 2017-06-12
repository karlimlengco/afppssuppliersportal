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
        'rfq_id',
        'upr_number',
        'rfq_number',
        'venue',
        'transaction_date',
    ];

}
