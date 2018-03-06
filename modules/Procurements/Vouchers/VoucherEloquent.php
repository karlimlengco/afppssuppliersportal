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

        'certified_signatory',
        'approver_signatory',
        'receiver_signatory',
    ];

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
            if($model->id == null)
            {
              $model->id = (string) \Uuid::generate();
            }
        });
    }

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
        'id',
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
        'final_tax_amount',
        'ewt_amount',
        'payment_no',
        'bank',
        'payment_date',

        'preaudit_date',

        'certify_date',
        'is_cash_avail',
        'subject_to_authority_to_debit_acc',
        'documents_completed',

        'journal_entry_date',
        'journal_entry_number',
        'or',

        'approval_date',

        'certified_by',
        'approver_id',
        'receiver_id',

        'amount',
        'days',
        'remarks',
        'preaudit_days',
        'preaudit_remarks',
        'jev_days',
        'jev_remarks',
        'certify_days',
        'certify_remarks',
        'check_days',
        'check_remarks',
        'approved_days',
        'approved_remarks',
        'released_days',
        'released_remarks',
        'received_days',
        'received_remarks',

        'action',
        'preaudit_action',
        'jev_action',
        'certify_action',
        'check_action',
        'approved_action',
        'released_action',
        'received_action',

        'certified_signatory',
        'approver_signatory',
        'receiver_signatory',
        'suppliers_address',

        'prepare_cheque_date',
        'counter_sign_date',
        'prepare_cheque_remarks',
        'prepare_cheque_action',
        'counter_sign_remarks',
        'counter_sign_action',

        'prepare_cheque_days',

        'counter_sign_days',
    ];

    /**
     * [certifier description]
     *
     * @return [type] [description]
     */
    public function certifier()
    {
        return $this->belongsTo('\Revlv\Settings\Signatories\SignatoryEloquent', 'certified_by');
    }

    /**
     * [approver description]
     *
     * @return [type] [description]
     */
    public function approver()
    {
        return $this->belongsTo('\Revlv\Settings\Signatories\SignatoryEloquent', 'approver_id');
    }

    /**
     * [receiver description]
     *
     * @return [type] [description]
     */
    public function receiver()
    {
        return $this->belongsTo('\Revlv\Settings\Signatories\SignatoryEloquent', 'receiver_id');
    }

    /**
     * [banks description]
     *
     * @return [type] [description]
     */
    public function banks()
    {
        return $this->belongsTo('\Revlv\Settings\Banks\BankEloquent', 'bank');
    }

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

    /**
     * [upr description]
     *
     * @return [type] [description]
     */
    public function upr()
    {
        return $this->belongsTo('\Revlv\Procurements\UnitPurchaseRequests\UnitPurchaseRequestEloquent', 'upr_id');
    }
}
