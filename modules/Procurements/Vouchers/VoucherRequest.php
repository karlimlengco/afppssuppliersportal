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
        'or',

        'approval_date',
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