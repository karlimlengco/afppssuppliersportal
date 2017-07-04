<div class="modal" id="signatory-modal">
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
                        {!! Form::selectField('requestor_id', 'Requested By', $signatory_list) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="twelve columns">
                        {!! Form::selectField('fund_signatory_id', 'Fund Certified Available', $signatory_list) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="twelve columns">
                        {!! Form::selectField('approver_id', 'Approved By', $signatory_list) !!}
                    </div>
                </div>
            </div>

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        {!! Form::close() !!}
    </div>
</div>
