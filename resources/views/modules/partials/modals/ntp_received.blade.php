<div class="modal" id="proceed-ntp-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="delete-form" action="{{ route($modelConfig['receive_ntp']['route'][0], $modelConfig['receive_ntp']['route'][1]) }}" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Receive Notice To Proceed</h1>
            </div>

            <div class="modal__dialogue__body">
                <div class="row">
                    <div class="six columns">
                        {!! Form::textField('received_by', 'Conforme') !!}
                    </div>
                    <div class="six columns">
                        {!! Form::textField('award_accepted_date', 'Date Received') !!}
                    </div>
                </div>
                {!! Form::textareaField('accepted_remarks', 'Remarks', null, ['rows' => 3])!!}
                {!! Form::textareaField('accepted_action', 'Action', null, ['rows' => 3])!!}
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="_method" type="hidden" value="PUT">
            </div>

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>