<div class="modal" id="biddings-philgeps-posting-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="delete-form" action="{{route('biddings.philgeps.store')}}" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">PhilGeps Posting</h1>
            </div>

            <div class="modal__dialogue__body">
                {!! Form::hidden('rfq_id', $data->id) !!}
                <div class="row">
                    <div class="four columns">
                        {!! Form::dateField('pp_transaction_date', 'Transaction Date') !!}
                    </div>
                    <div class="four columns">
                        {!! Form::textField('newspaper', 'Newspaper') !!}
                    </div>
                    <div class="four columns">
                        {!! Form::selectField('status', 'Status', ['1' => 'Approved', '0' => 'Need Repost']) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="six columns">
                        {!! Form::textField('philgeps_number', 'PhilGeps number') !!}
                    </div>
                    <div class="six columns">
                        {!! Form::dateField('pp_philgeps_posting', 'PhilGeps From Posting') !!}
                    </div>
                </div>

                <div class="row">
                    {!! Form::textareaField('remarks', 'Remarks', null, ['rows'=>3])!!}
                </div>

                <div class="row">
                    {!! Form::textareaField('action', 'Action', null, ['rows'=>3])!!}
                </div>

                <input name="upr_id" type="hidden" value="{{ $data->id }}">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="_method" type="hidden" value="POST">
            </div>

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>