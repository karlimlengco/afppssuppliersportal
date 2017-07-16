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
                <div class="row">
                    <div class="six columns">
                        {!!Form::textField('certify_date', 'Certify Date')!!}
                    </div>
                    <div class="six columns">
                        {!!Form::booleanField('is_cash_avail', 'Cash Available')!!}
                    </div>
                </div>
                <div class="row">
                    <div class="six columns">
                        {!!Form::booleanField('subject_to_authority_to_debit_acc', 'Subject to Authority to Debit Account ')!!}
                    </div>
                    <div class="six columns">
                        {!!Form::booleanField('documents_completed', 'Supporting documents complete')!!}
                    </div>
                </div>

                {!!Form::textareaField('certify_remarks', 'Remarks', null, ['rows' => 3])!!}
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="_method" type="hidden" value="POST">
            </div>

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>