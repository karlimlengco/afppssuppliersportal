@section('title')
Canvassing
@stop

@section('contents')
{!! Form::open($modelConfig['store']) !!}
<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back">
            <i class="nc-icon-mini arrows-1_tail-left"></i>
        </a>
        <button type="submit" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
            <div class="row">
                <div class="six columns">
                    {!! Form::selectField('rfq_id', 'RFQ Number', $rfq_list) !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('canvass_date', 'Canvassing Date') !!}
                </div>
            </div>


        {!!Form::close()!!}
    </div>
</div>

@stop
@section('scripts')
<script type="text/javascript">

    // datepicker
    // pickmeup('#id-field-canvass_date', {
    //     format  : 'Y-m-d'
    // });

    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-canvass_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    // end datepicker
</script>
@stop