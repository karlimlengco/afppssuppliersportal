<div class="modal" id="signatory-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        {!! Form::model($data, $modelConfig['update']) !!}
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Signatory</h1>
            </div>

            <div class="modal__dialogue__body">
                <div class="row">
                    <div class="twelve columns">
                        {!! Form::selectField('inspection_signatory', 'Inspection Signatory', $signatory_list) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="twelve columns">
                        {!! Form::selectField('acceptance_signatory', 'Acceptance Signatory', $signatory_list) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="twelve columns">
                        {!! Form::selectField('sao_signatory', 'SAO Signatory', $signatory_list) !!}
                    </div>
                </div>
            </div>

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        {!! Form::close() !!}
    </div>
</div>