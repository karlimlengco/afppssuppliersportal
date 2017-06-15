@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
@stop
@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3>PhilGeps Posting</h3>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        {!! Form::open($modelConfig['store']) !!}
            <div class="row">
                <div class="six columns">
                    {!! Form::selectField('rfq_id', 'RFQ Number', $rfq_list) !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('transaction_date', 'Transaction Date') !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::textField('philgeps_number', 'PhilGeps number') !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('philgeps_posting', 'PhilGeps From Posting') !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::textField('deadline_rfq', 'Deadline To Submit RFQ') !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('opening_time', 'Opening Time') !!}
                </div>
            </div>

            <button type="reset" class="button"> <a href="{{route($indexRoute)}}">Back</a> </button>
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

    pickmeup('#id-field-deadline_rfq', {
        format  : 'Y-m-d'
    });

    pickmeup('#id-field-philgeps_posting', {
        format  : 'Y-m-d'
    });
    // end datepicker
</script>
@stop