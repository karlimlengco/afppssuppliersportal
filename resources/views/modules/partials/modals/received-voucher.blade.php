<div class="modal" id="received-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="delete-form"  action="{{route('procurements.vouchers.received', $data->id)}}" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Payment Received Date</h1>
            </div>

            <div class="modal__dialogue__body">
                <div class="row">
                    <div class="twelve columns">
                        {!! Form::textField('payment_received_date', '') !!}
                    </div>
                </div>
                <div class="row">
                    <div class="six columns">
                        {!! Form::textField('or', 'Official Receipt #') !!}
                    </div>
                </div>
                {!! Form::textareaField('received_remarks', 'Remarks', null, ['rows' => 3]) !!}
                <div class="row">
                    <div class="twelve columns">
                        {!! Form::textareaField('received_action', 'Action', null, ['rows'=>3]) !!}
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