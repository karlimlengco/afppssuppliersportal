<div class="modal" id="ntp-philgeps-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="delete-form" action="{{ route($modelConfig['ntp-philgeps']['route'][0], $modelConfig['ntp-philgeps']['route'][1]) }}" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Philgeps Posting of NTP</h1>
            </div>

            <div class="modal__dialogue__body">
                {!! Form::dateField('philgeps_posting', 'Philgeps Posting Date')!!}
                {!! Form::textareaField('philgeps_remarks', 'Remarks', null, ['rows' => 3])!!}
                {!! Form::textareaField('philgeps_action', 'Action', null, ['rows' => 3])!!}
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="_method" type="hidden" value="PUT">
            </div>

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>