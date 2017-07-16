<div class="modal" id="coa-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="accept-form" action="{{route('procurements.purchase-orders.coa-approved', $data->id)}}" accept-charset="UTF-8" enctype="multipart/form-data">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Confirm Approval</h1>
            </div>

            <div class="modal__dialogue__body">
                {!!Form::textField('coa_approved_date', 'Approval Date')!!}
                {!!Form::fileField('file', 'Scan copy of approval')!!}
                {!! Form::textareaField('coa_remarks', 'Remarks', null,['rows' => 3]) !!}
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