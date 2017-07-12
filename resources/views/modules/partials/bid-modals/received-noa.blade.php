<div class="modal" id="notice-acceptance-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="delete-form" action="{{ route('biddings.noa-acceptance.store') }}" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Received NOA</h1>
            </div>

            <div class="modal__dialogue__body">
                <div class="row">
                    <div class="twelve columns">
                        {!! Form::selectField('supplier_id', 'Supplier', $supplier_lists) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="six columns">
                        {!! Form::textField('received_noa', 'Received Date') !!}
                    </div>
                    <div class="six columns">
                        {!! Form::textField('received_by', 'Received By') !!}
                    </div>
                </div>
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="rfb_id" type="hidden" value="{{$data->id}}">
                <input name="_method" type="hidden" value="POST">
            </div>

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>