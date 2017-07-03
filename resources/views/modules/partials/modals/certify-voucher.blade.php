<div class="modal" id="certify-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="accept-form" action="{{route('procurements.vouchers.certify', $data->id)}}" accept-charset="UTF-8" enctype="multipart/form-data">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Certify Voucher</h1>
            </div>

            <div class="modal__dialogue__body">
                {!!Form::textField('certify_date', 'PreAudit Date')!!}
                {!!Form::booleanField('is_cash_avail', 'Cash Available')!!}
                {!!Form::booleanField('subject_to_authority_to_debit_acc', 'Subject to Authority to Debit Account ')!!}
                {!!Form::booleanField('documents_completed', 'Supporting documents complete')!!}
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="_method" type="hidden" value="POST">
            </div>

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>