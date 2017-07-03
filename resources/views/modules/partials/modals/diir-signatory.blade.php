<div class="modal" id="signatories-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        {!! Form::model($data, $modelConfig['update']) !!}
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Signatories</h1>
            </div>

            <div class="modal__dialogue__body">
                <div class="row">
                    <div class="twelve columns">
                        {!! Form::selectField('received_by', 'Received By', $signatory_list) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="twelve columns">
                        {!! Form::selectField('approved_by', 'Approved By', $signatory_list) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="twelve columns">
                        {!! Form::selectField('issued_by', 'Issued By', $signatory_list) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="twelve columns">
                        {!! Form::selectField('requested_by', 'Requested By', $signatory_list) !!}
                    </div>
                </div>
            </div>

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        {!! Form::close() !!}
    </div>
</div>