<div class="modal" id="proponent-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="delete-form" action="{{ route($modelConfig['add_proponents']['route']) }}" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Add Proponents</h1>
            </div>

            <div class="modal__dialogue__body">
                <div class="row">
                    <div class="twelve columns">
                        {{-- {!! Form::selectField('proponents', 'Proponents', $supplier_lists) !!} --}}

                        <label class="label">Proponents</label>
                        {!! Form::select('proponents',  $supplier_lists, null, ['class' => 'selectize', 'id' => 'id-field-proponent_id']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="twelve columns">
                        {!! Form::dateField('date_processed', 'Date Processed') !!}
                    </div>
                </div>

                <div class="row">
                    <div class="twelve columns">
                        {!! Form::textareaField('note', 'Note', null, ['rows' => 2]) !!}
                    </div>
                </div>

                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="rfq_id" type="hidden" value="{{$data->id}}">
                <input name="_method" type="hidden" value="POST">
            </div>

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>