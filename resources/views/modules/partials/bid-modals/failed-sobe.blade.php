<div class="modal" id="fail-pq-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="delete-form" action="{{route('biddings.bid-openings.failed')}}" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Failed SOBE</h1>
            </div>

            <div class="modal__dialogue__body">
                {!! Form::hidden('rfq_id', $data->id) !!}
                <div class="row">
                    {!! Form::dateField('failed_date', 'Failed date')!!}
                </div>

                <div class="row">
                    {!! Form::textareaField('failed_remarks', 'Remarks', null, ['rows'=>3])!!}
                </div>
                <div class="row">
                    {!! Form::selectField('status', 'Status', ['continue' => 'Continue', 'cancel' => 'Cancelled'])!!}
                </div>

                <input name="id" type="hidden" value="{{ $data->id }}">
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="_method" type="hidden" value="POST">
            </div>

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>