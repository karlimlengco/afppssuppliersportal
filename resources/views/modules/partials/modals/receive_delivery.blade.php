<div class="modal" id="received-item-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="delete-form" action="" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Receive Delivery </h1>
            </div>

            <div class="modal__dialogue__body">

                <div class="row">
                    <div class="six columns">
                        {!! Form::textField('delivery_date', 'Delivery Date') !!}
                    </div>
                    <div class="six columns">
                        {!! Form::textField('delivery_number', 'Delivery Number') !!}
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