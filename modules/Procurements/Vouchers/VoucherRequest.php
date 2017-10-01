<?php

namespace Revlv\Procurements\Vouchers;

use Revlv\BaseRequest;

class VoucherRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected $whitelist = [
        'upr_id',
        'rfq_id',
        'prepared_by',
        'rfq_number',
        'upr_number',
        'voucher_transaction_date',
        'bir_address',
        'final_tax',
        'expanded_witholding_tax',
        'update_remarks',

        'preaudit_date',

        'certify_date',
        'is_cash_avail',
        'subject_to_authority_to_debit_acc',
        'documents_completed',

        'journal_entry_date',
        'journal_entry_number',
        'final_tax_amount',
        'ewt_amount',
        'payment_no',
        'bank',
        'payment_date',
        'or',

        'approval_date',
        'certified_by',
        'approver_id',
        'receiver_id',

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
        'amount',

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
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'rfq_id'                    => 'required',
            'voucher_transaction_date'  => 'required',
            'bir_address'               => 'required',
        ];
    }
}