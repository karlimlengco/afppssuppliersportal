<div class="modal" id="philgeps-posting-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="delete-form" action="{{route('procurements.philgeps-posting.store')}}" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">PhilGeps Posting</h1>
            </div>

            <div class="modal__dialogue__body">
                @if($data->rfq)
                {!! Form::hidden('rfq_id', $data->rfq->id) !!}
                @endif
                <div class="row">
                    <div class="six columns">
                        {!! Form::dateField('transaction_date', 'Transaction Date') !!}
                    </div>
                    <div class="six columns">
                        {!! Form::selectField('status', 'Status', ['1' => 'Approved', '0' => 'Need Repost']) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="six columns">
                        {!! Form::textField('philgeps_number', 'PhilGeps number') !!}
                    </div>
                    <div class="six columns">
                        {!! Form::dateField('philgeps_posting', 'PhilGeps From Posting') !!}
                    </div>
                </div>

                <div class="row">
                    <div class="six columns">
                        {!! Form::dateField('deadline_rfq', 'Deadline To Submit RFQ', ($data->rfq) ? $data->rfq->deadline : "") !!}
                    </div>
                    <div class="six columns">
                        {!! Form::textField('pp_opening_time', 'Opening Time', ($data->rfq) ? $data->rfq->opening_time : "") !!}
                    </div>
                </div>

                <div class="row">
                    <div class="twelve columns">
                        {!! Form::textareaField('remarks', 'Remarks', null, ['rows' => 3]) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="twelve columns">
                        {!! Form::textareaField('action', 'Action', null, ['rows' => 3]) !!}
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