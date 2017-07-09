@section('title')
Minutes of the Meeting
@stop

@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
@stop

@section('modal')
    {!! Form::model($data, $modelConfig['update']) !!}
@stop

@section('contents')


<div class="row">
    <div class="twelve columns align-right utility utility--align-right">

        <button type="submit" id="edit-button" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

        <a href="{{route($indexRoute, $data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

    </div>
</div>
<div class="row">
    <div class="twelve columns">

            <div class="row">
                <div class="four columns">
                    {!! Form::textField('date_opened', 'Meeting Date') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('time_opened', 'Meeting Time') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('time_closed', 'Adjourned Time') !!}
                </div>
            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::tagField('members', 'Members', $signatory_lists,  $members ) !!}
                </div>
            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::tagField('canvass', 'Canvass', $canvass_lists, $canvass )!!}
                </div>
            </div>


            <div class="row">
                <div class="twelve columns">
                    {!! Form::textareaField('venue', 'Venue', null, ['rows'=>3]) !!}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>



@stop

@section('scripts')
<script src="/vendors/timepicker/timepicker.min.js"></script>

<script type="text/javascript">

    var timepicker1 = new TimePicker(['id-field-time_opened', 'id-field-time_closed'], {
        lang: 'en',
        theme: 'dark'
    });

    timepicker1.on('change', function(evt){
      var value = (evt.hour || '00') + ':' + (evt.minute || '00');
      evt.element.value = value;
    });

    // datepicker
    var date_opened = new Pikaday(
    {
        field: document.getElementById('id-field-date_opened'),
        firstDay: 1,
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    // end datepicker
</script>
@stop