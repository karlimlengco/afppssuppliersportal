@section('title')
Holidays
@stop

@section('contents')

{!! Form::open($modelConfig['store']) !!}

<div class="row">
    <div class="twelve columns align-left utility utility--align-right">
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button type="submit" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

    </div>
</div>
<div class="row">
    <div class="twelve columns">
        {!! Form::textField('name', 'Name') !!}
        {!! Form::textField('holiday_date', 'holiday_date') !!}

        <button type="reset" class="button"> <a href="{{route($indexRoute)}}">Back</a> </button>
        <button type="submit" class="button">Save</button>

    </div>
</div>

{!!Form::close()!!}
@stop
@section('scripts')
<script type="text/javascript">
    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-holiday_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

</script>
@stop