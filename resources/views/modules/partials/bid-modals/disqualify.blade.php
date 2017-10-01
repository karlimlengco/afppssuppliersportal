<div class="modal" id="disqualify-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="dq-form" action="" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Disqualify Proponent <br><strong id="proponent"></strong></h1>
            </div>

            <div class="modal__dialogue__body">
                <div class="row">
                    {!! Form::dateField('disqualification_date', 'Disqualification Date')!!}
                </div>
                <div class="row">
                    {!! Form::textareaField('disqualification_remarks', 'Remarks', null, ['rows'=>3])!!}
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