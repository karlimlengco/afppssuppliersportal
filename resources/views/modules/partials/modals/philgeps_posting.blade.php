<div class="modal" id="philgeps-posting-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="delete-form" action="#" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Receive Notice To Proceed</h1>
            </div>

            <div class="modal__dialogue__body">
                {!! Form::hidden('rfq_id', $data->id) !!}
                <div class="row">
                    <div class="six columns">
                        {!! Form::textField('transaction_date', 'Transaction Date') !!}
                    </div>
                </div>

                <div class="row">
                    <div class="six columns">
                        {!! Form::textField('philgeps_number', 'PhilGeps number') !!}
                    </div>
                    <div class="six columns">
                        {!! Form::textField('philgeps_posting', 'PhilGeps From Posting') !!}
                    </div>
                </div>

                <div class="row">
                    <div class="six columns">
                        {!! Form::textField('deadline_rfq', 'Deadline To Submit RFQ') !!}
                    </div>
                    <div class="six columns">
                        {!! Form::textField('opening_time', 'Opening Time') !!}
                    </div>
                </div>

                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="_method" type="hidden" value="PUT">
            </div>

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>