<?php

namespace Revlv\Procurements\Vouchers;

use Illuminate\Database\Eloquent\Model;

class VoucherEloquent extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'vouchers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'upr_id',
        'rfq_id',
        'prepared_by',
        'rfq_number',
        'upr_number',
        'transaction_date',
        'bir_address',
        'final_tax',
        'payment_release_date',
        'process_releaser',
        'payment_received_date',
        'payment_receiver',
        'expanded_witholding_tax',
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
     * [recevier description]
     *
     * @return [type] [description]
     */
    public function recevier()
    {
        return $this->belongsTo('\App\User', 'payment_receiver');
    }

    /**
     * [releaser description]
     *
     * @return [type] [description]
     */
    public function releaser()
    {
        return $this->belongsTo('\App\User', 'process_releaser');
    }

}
