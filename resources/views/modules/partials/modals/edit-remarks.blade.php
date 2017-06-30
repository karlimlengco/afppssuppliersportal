<div class="modal" id="edit-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Reason of Edit</h1>
            </div>

            <div class="modal__dialogue__body">
                <div class="row">
                    <div class="twelve columns">
                        {!! Form::textareaField('update_remarks', '', null, ['rows' => 3, 'required']) !!}
                    </div>
                </div>
            </div>

            <div class="modal__dialogue__foot">
                <button type="submit" class="button">Proceed</button>
            </div>

    </div>
</div>