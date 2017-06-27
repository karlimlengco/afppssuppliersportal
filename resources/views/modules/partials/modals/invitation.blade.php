<div class="modal"  id="invitation-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="delete-form" action="{{route('procurements.ispq.store-by-rfq',$data->id)}}" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Create Invitation to Submit Price Quotation</h1>
            </div>

            <div class="modal__dialogue__body">

                <div class="row">
                    <div class="six columns">
                        {!! Form::textField('canvassing_date', 'Canvass Date') !!}
                    </div>
                    <div class="six columns">
                        {!! Form::textField('canvassing_time', 'Canvass Time') !!}
                    </div>
                </div>


                <div class="row">
                    <div class="twelve columns">
                        {!! Form::selectField('signatory_id', 'Signatories', $signatory_lists) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="twelve columns">
                        {!! Form::textareaField('venue', 'Venue', null, ['rows'=>3]) !!}
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