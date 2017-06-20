<div class="modal" id="create-delivery-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="delete-form"  action="{{ route($modelConfig['create_nod']['route'][0], $modelConfig['create_nod']['route'][1]) }}" accept-charset="UTF-8">
            <button class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Create Notice Of Delivery</h1>
            </div>

            <div class="modal__dialogue__body">
                <div class="row">
                    <div class="six columns">
                        {!! Form::textField('transaction_date', 'Transaction Date') !!}
                    </div>
                    <div class="six columns">
                        {!! Form::textField('expected_date', 'Expected Delivery Date') !!}
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