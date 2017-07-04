<?php

namespace Revlv\Procurements\Vouchers;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class VoucherEloquent extends Model implements  AuditableContract
{
    use Auditable;

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'update_remarks',
        'transaction_date',
        'payment_release_date',
        'payment_received_date',
        'preaudit_date',
        'certify_date',
        'journal_entry_date',
        'approval_date',
    ];

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
        'update_remarks',
        'transaction_date',
        'bir_address',
        'final_tax',
        'payment_release_date',
        'process_releaser',
        'payment_received_date',
        'payment_receiver',
        'expanded_witholding_tax',


        'preaudit_date',

        'certify_date',
        'is_cash_avail',
        'subject_to_authority_to_debit_acc',
        'documents_completed',

        'journal_entry_date',
        'journal_entry_number',
        'or',

        'approval_date',
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
