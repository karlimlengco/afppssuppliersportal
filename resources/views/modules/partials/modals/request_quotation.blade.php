<div class="modal" id="process-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="delete-form" action="{{ route($modelConfig['request_quotation']['route']) }}" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Create Blank Request For Quotation</h1>
            </div>

            <div class="modal__dialogue__body">
                <div class="row">
                    <div class="four columns">
                        {!! Form::dateField('transaction_date', 'Transaction Date') !!}
                    </div>
                    <div class="four columns">
                        {!! Form::dateField('deadline', 'Deadline to submit') !!}
                    </div>
                    <div class="four columns">
                        {!! Form::textField('opening_time', 'Opening Time') !!}
                    </div>
                </div>

                <div class="row">
                    {!! Form::textareaField('remarks', 'Remarks', null, ['rows'=>3])!!}
                </div>
                <div class="row">
                    {!! Form::textareaField('action', 'Action', null, ['rows'=>3])!!}
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