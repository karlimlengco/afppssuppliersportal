<div class="modal"  id="mfo-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="delete-form" action="{{ route($modelConfig['mfo_approval']['route'][0], $modelConfig['mfo_approval']['route'][1]) }}" accept-charset="UTF-8">
            <button class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">MFO Approval</h1>
            </div>

            <div class="modal__dialogue__body">
                <div class="row">
                    <div class="six columns">
                        {!! Form::selectField('mfo_has_issue', 'Has Issue', ['yes' => 'Yes', 'no' => 'No']) !!}
                    </div>
                    <div class="six columns">
                        {!! Form::textField('mfo_released_date', 'Date Released') !!}
                    </div>
                </div>
                <div class="row">
                    <div class="six columns">
                        {!! Form::textField('mfo_received_date', 'Date Received') !!}
                    </div>
                    <div class="six columns">
                        {!! Form::textareaField('mfo_remarks', 'Remarks', null, ['rows' => 2]) !!}
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