<div class="modal" id="journal-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="accept-form" action="{{route('procurements.vouchers.journal', $data->id)}}" accept-charset="UTF-8" enctype="multipart/form-data">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Journal Entry Voucher</h1>
            </div>

            <div class="modal__dialogue__body">
                {!!Form::textField('journal_entry_date', 'Journal Entry Date')!!}
                {!!Form::textField('journal_entry_number', 'Journal Entry Number')!!}
                {!!Form::textareaField('jev_remarks', 'Remarks', null, ['rows' => 3])!!}
                {!!Form::textareaField('jev_action', 'Action', null, ['rows' => 3])!!}
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="_method" type="hidden" value="POST">
            </div>

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>