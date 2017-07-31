<div class="modal" id="failed-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="accept-form" action="{{route('procurements.canvassing.failed')}}" accept-charset="UTF-8" enctype="multipart/form-data">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Failed Bid</h1>
            </div>

            <div class="modal__dialogue__body">
                {!!Form::dateField('date_failed', 'Date Failed')!!}
                {!!Form::textareaField('failed_remarks', 'Reason for failure', null, ['rows' => 3])!!}

                <input name="upr_id" type="hidden" value="{{ $data->upr_id }}">
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