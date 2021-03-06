<div class="modal" id="ntp-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="award-form" action="{{route('procurements.ntp.store')}}" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Notice To Proceed Confirmation</h1>
            </div>

            <div class="modal__dialogue__body">
                <p>Notice to Proceed will be created for this PO.</p>
                {!! Form::dateField('preparared_date', 'Date Prepared')!!}
                {!! Form::textareaField('remarks', 'Remarks', null, ['rows' => 3])!!}
                {!! Form::textareaField('action', 'Action', null, ['rows' => 3])!!}
                @if($data->purchase_order)
                <input name="po_id" type="hidden" value="{{ $data->purchase_order->id }}">
                @endif
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="_method" type="hidden" value="POST">
            </div>

            <div class="modal__dialogue__foot">
                <button class="button" id="ntp-submit">Proceed</button>
            </div>

        </form>
    </div>
</div>