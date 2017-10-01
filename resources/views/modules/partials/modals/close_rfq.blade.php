<div class="modal" id="close-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
     <form method="POST" action="{{route('procurements.blank-rfq.closed')}}" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Closing RFQ</h1>
            </div>

            <div class="modal__dialogue__body">
                <p>After closing this Request for Quotation you will not be able to add proponents or update.</p>

                {!! Form::dateField('completed_at', 'Close Date', null, ['required']) !!}
                {!! Form::textareaField('close_remarks', 'Close Remarks', null, ['rows'=> 3]) !!}
                {!! Form::textareaField('close_action', 'Close Action', null, ['rows'=> 3]) !!}

            </div>
                <input name="rfq_id" type="hidden" value="{{ $data->id }}">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="_method" type="hidden" value="POST">

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>
        </form>
    </div>
</div>