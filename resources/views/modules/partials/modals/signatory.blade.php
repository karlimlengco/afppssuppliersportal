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
                        {!! Form::selectField('signatory_id', '', $signatory_list, $data->signatory_id) !!}
                    </div>
                </div>
            </div>

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        {!! Form::close() !!}
    </div>
</div>