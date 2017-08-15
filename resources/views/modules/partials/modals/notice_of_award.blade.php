<div class="modal" id="award-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="award-form" action="#" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Award Confirmation</h1>
            </div>

            <div class="modal__dialogue__body">
                {!! Form::textField('awarded_date', 'Award Date')!!}

                <div class="row">
                    <div class="six columns">
                        {!! Form::selectField('awarded_by', 'Award By', $current_signs)!!}
                    </div>
                    <div class="six columns">
                        {!! Form::selectField('seconded_by', 'Seconded By', $current_signs)!!}
                    </div>
                </div>

                {!! Form::textareaField('resolution', 'Resolution', "", ['rows' => '4']) !!}

                {!! Form::textareaField('remarks', 'Remarks', null, ['rows' => '4']) !!}
                {!! Form::textareaField('action', 'Action', null, ['rows' => '4']) !!}


                <p>You will award this to <strong><span id='proponent'></span></strong></p>

                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="_method" type="hidden" value="POST">
            </div>

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>