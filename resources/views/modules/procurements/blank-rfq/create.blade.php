@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
@stop
@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3>Request For Quotation</h3>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        {!! Form::open($modelConfig['store']) !!}
            <div class="row">
                <div class="six columns">
                    {!! Form::selectField('upr_id', 'UPR Number', $upr_list) !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('transaction_date', 'Transaction Date') !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::textField('deadline', 'Deadline to submit') !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('opening_time', 'Opening Time') !!}
                    {{-- <div class="form-group">
                        <label for="id-field-opening_time" class="label">Opening Time</label>
                        <input class="input" id="time" name="opening_time" type="text"
                        >
                    </div> --}}
                </div>
            </div>

             <a class="button" href="{{route($indexRoute)}}">Back</a>
            <button type="submit" class="button">Save</button>

        {!!Form::close()!!}
    </div>
</div>

@stop
@section('scripts')
<script src="/vendors/timepicker/timepicker.min.js"></script>

<script type="text/javascript">

    var timepicker = new TimePicker('id-field-opening_time', {
        lang: 'en',
        theme: 'dark'
    });

    timepicker.on('change', function(evt){
      var value = (evt.hour || '00') + ':' + (evt.minute || '00');
      evt.element.value = value;
    });

    // datepicker
    pickmeup('#id-field-transaction_date', {
        format  : 'Y-m-d'
    });

    pickmeup('#id-field-deadline', {
        format  : 'Y-m-d'
    });
    // end datepicker
</script>
@stop