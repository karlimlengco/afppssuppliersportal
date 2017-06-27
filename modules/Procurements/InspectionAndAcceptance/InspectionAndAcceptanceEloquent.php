<?php

namespace Revlv\Procurements\InspectionAndAcceptance;

use Illuminate\Database\Eloquent\Model;

class InspectionAndAcceptanceEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'inspection_acceptance_report';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rfq_id',
        'upr_id',
        'dr_id',
        'delivery_number',
        'rfq_number',
        'upr_number',
        'inspection_date',
        'nature_of_delivery',
        'findings',
        'prepared_by',
        'accepted_by',
        'recommendation',
        'accepted_date',
        'status',
        'inspection_signatory',
        'acceptance_signatory',
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
     * [inspector description]
     *
     * @return [type] [description]
     */
    public function inspector()
    {
        return $this->belongsTo('\Revlv\Settings\Signatories\SignatoryEloquent', 'inspection_signatory');
    }

    /**
     * [acceptor description]
     *
     * @return [type] [description]
     */
    public function acceptor()
    {
        return $this->belongsTo('\Revlv\Settings\Signatories\SignatoryEloquent', 'acceptance_signatory');
    }

    /**
     * [invoices description]
     *
     * @return [type] [description]
     */
    public function invoices()
    {
        return $this->hasMany('\Revlv\Procurements\InspectionAndAcceptance\Invoices\InvoiceEloquent','acceptance_id');
    }
}
