<div class="modal"  id="invitation-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="delete-form" action="{{route('procurements.ispq.store-by-rfq',$data->id)}}" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Prepare Invitation to Submit Price Quotation</h1>
            </div>

            <div class="modal__dialogue__body">

                <div class="row">
                    <div class="four columns">
                        {!! Form::dateField('canvassing_date', 'Canvass Date') !!}
                    </div>
                    <div class="four columns">
                        {!! Form::textField('canvassing_time', 'Canvass Time', ($data->rfq) ? $data->rfq->opening_time : "") !!}
                    </div>
                    <div class="four columns">
                        {!! Form::dateField('transaction_dates', 'Transaction Date') !!}
                    </div>
                </div>


                <div class="row">
                    <div class="twelve columns">
                        {{-- {!! Form::selectField('signatory_id', 'Signatories', $signatory_list) !!} --}}

                        <label class="label">Signatory</label>
                        {!! Form::select('signatory_id',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-signatory_id']) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="twelve columns">
                        {!! Form::textareaField('venue', 'Venue', null, ['rows'=>3]) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="twelve columns">
                        {!! Form::textareaField('remarks', 'Remarks', null, ['rows'=>3]) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="twelve columns">
                        {!! Form::textareaField('action', 'Action', null, ['rows'=>3]) !!}
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