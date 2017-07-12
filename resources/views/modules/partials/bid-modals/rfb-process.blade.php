<div class="modal" id="rfb-process-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="delete-form" action="{{ route('biddings.request-for-bids.store') }}" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Release Request for Bidding</h1>
            </div>

            <div class="modal__dialogue__body">
                <div class="row">
                    <div class="four columns">
                        {!! Form::textField('rfb_transaction_date', 'Transaction Date') !!}
                    </div>
                    <div class="four columns">
                        {!! Form::textField('released_date', 'Released Date') !!}
                    </div>
                    <div class="four columns">
                        {!! Form::selectField('bac_id', 'BacSec', $bacsec_list) !!}
                    </div>
                </div>

                <div class="row">
                    {!! Form::textareaField('remarks', 'Remarks', null, ['rows'=>3])!!}
                </div>

                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="upr_id" type="hidden" value="{{$data->id}}">
                <input name="_method" type="hidden" value="POST">
            </div>

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>