<div class="modal" id="open_canvass-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="delete-form" action="{{ route('procurements.canvassing.opening', $data->id) }}" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Opening Canvass</h1>
            </div>

            <div class="modal__dialogue__body">
                <div class="row">
                    <div class="six columns">
                        {!! Form::dateField('open_canvass_date', 'Canvass Date') !!}
                    </div>
                    <div class="six columns">
                        {!! Form::textField('open_canvass_time', 'Canvass Time') !!}
                    </div>
                </div>
                <div class="row">
                    <div class="six columns">
                        {{-- {!! Form::selectField('presiding_officer', 'Presiding Officer', $signatory_list) !!} --}}

                        <label class="label">Presiding Officer</label>
                        {!! Form::select('presiding_officer',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-presiding_officer']) !!}
                    </div>
                    <div class="six columns">
                        {{-- {!! Form::selectField('chief', 'Chief', $signatory_list) !!} --}}

                        <label class="label">Chief</label>
                        {!! Form::select('chief',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-chief']) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="six columns">
                        {{-- {!! Form::selectField('unit_head', 'Unit Head', $signatory_list) !!} --}}

                        <label class="label">Unit Head</label>
                        {!! Form::select('unit_head',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-unit_head']) !!}
                    </div>
                    <div class="six columns">
                        {{-- {!! Form::selectField('mfo', 'MFO', $signatory_list) !!} --}}

                        <label class="label">MFO</label>
                        {!! Form::select('mfo',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-mfo']) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="six columns">
                        {{-- {!! Form::selectField('legal', 'Legal', $signatory_list) !!} --}}

                        <label class="label">Legal</label>
                        {!! Form::select('legal',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-legal']) !!}
                    </div>
                    <div class="six columns">
                        {{-- {!! Form::selectField('secretary', 'Secretary', $signatory_list) !!} --}}

                        <label class="label">Secretary</label>
                        {!! Form::select('secretary',  $signatory_list, null, ['class' => 'selectize', 'id' => 'id-field-secretary']) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="twelve columns">
                        {!! Form::textField('other_attendees', 'Other Attendees') !!}
                    </div>
                </div>
                <div class="row">
                    <div class="twelve columns">
                        {!! Form::textareaField('remarks', 'Remarks', null, ['rows' => 3]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="twelve columns">
                        {!! Form::textareaField('action', 'Action', null, ['rows' => 3]) !!}
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