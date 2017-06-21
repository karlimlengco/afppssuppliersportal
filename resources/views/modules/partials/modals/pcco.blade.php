<div class="modal" id="pcco-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="delete-form" action="{{ route($modelConfig['pcco_approval']['route'][0], $modelConfig['pcco_approval']['route'][1]) }}" accept-charset="UTF-8">
            <button class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">PCCO Approval  </h1>
            </div>

            <div class="modal__dialogue__body">
                <div class="row">
                    <div class="four columns">
                        {!! Form::selectField('pcco_has_issue', 'Has Issue', ['yes' => 'Yes', 'no' => 'No']) !!}
                    </div>
                    <div class="four columns">
                        {!! Form::textField('pcco_released_date', 'Date Released') !!}
                    </div>
                    <div class="four columns">
                        {!! Form::textField('pcco_received_date', 'Date Received') !!}
                    </div>
                </div>

                <div class="row">
                    <div class="twelve columns">
                        {!! Form::textareaField('pcco_remarks', 'Remarks', null, ['rows' => 2]) !!}
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