<div class="modal" id="release-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="delete-form"  action="{{route('procurements.vouchers.released', $data->id)}}" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">LDDAP-ADA Payment Release Date</h1>
            </div>

            <div class="modal__dialogue__body">
                <div class="row">
                    <div class="twelve columns">
                        {!! Form::textField('payment_release_date', '') !!}
                    </div>
                </div>

                <div class="row">
                    <div class="four columns">
                        {!! Form::textField('payment_no', 'Check / ADA No') !!}
                    </div>
                    <div class="four columns">
                        {!! Form::selectField('bank', 'Bank', $bank_list) !!}
                    </div>
                    <div class="four columns">
                        {!! Form::textField('payment_date', 'Check Date') !!}
                    </div>
                </div>

                {!! Form::textareaField('released_remarks', 'Remarks', null, ['rows' => 3]) !!}

                <div class="row">
                    <div class="twelve columns">
                        {!! Form::textareaField('released_action', 'Action', null, ['rows'=>3]) !!}
                    </div>
                </div>
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="_method" type="hidden" value="POST">
            </div>

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>