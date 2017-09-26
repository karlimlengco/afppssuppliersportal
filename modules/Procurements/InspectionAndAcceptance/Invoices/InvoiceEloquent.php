<?php

namespace Revlv\Procurements\InspectionAndAcceptance\Invoices;

use Illuminate\Database\Eloquent\Model;

class InvoiceEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'inspection_acceptance_invoices';

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
            $model->id = (string) \Uuid::generate();
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'acceptance_id',
        'invoice_number',
        'invoice_date',
    ];

    /**
     * [accetance description]
     *
     * @return [type] [description]
     */
    public function accetance()
    {
        return $this->belongsTo('\Revlv\Procurements\InspectionAndAcceptance\InspectionAndAcceptanceEloquent', 'acceptance_id');
    }
}
