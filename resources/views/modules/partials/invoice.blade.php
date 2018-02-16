<div class="modal" id="invoice-modal" style="z-index:999999999">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="invoice-form" accept-charset="UTF-8" action="{{route('biddings.inspection-and-acceptance.add-invoice',$data->id)}}">

            <div class="moda__dialogue__head">
                <h1 class="modal__title">New Invoice</h1>
            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::textField('invoice_number', 'Invoice Number', null, ['required']) !!}
                </div>
                <div class="twelve columns">
                    <input type="date" name="invoice_date" class="input">
                    {{-- {!! Form::text('invoice_date', null, ['class' => 'input', 'id' => 'id-field-invoice_date']) !!} --}}
                    {{-- {!! Form::dateField('invoice_date', 'Invoice Date', null, ['required']) !!} --}}
                </div>
            </div>
            <div class="modal__dialogue__body">

                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="_method" type="hidden" value="POST">
            </div>

            <div class="modal__dialogue__foot">
                <button type="button" class="button pull-left" id='close-signatory-button'>Cancel</button>
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>