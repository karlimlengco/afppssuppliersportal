@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3>Canvassing</h3>
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
                    {!! Form::textField('canvass_date', 'Canvassing Date') !!}
                </div>
            </div>

            <button type="reset" class="button"> <a href="{{route($indexRoute)}}">Back</a> </button>
            <button type="submit" class="button">Save</button>

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