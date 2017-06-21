@section('title')
Delivered Items Inspection
@stop

@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3> </h3>
    </div>
</div>

{!! Form::open($modelConfig['store']) !!}
<div class="row">
    <div class="twelve columns">
        <div class="row">
            <div class="twelve columns">
                {!! Form::selectField('dr_id', 'RFQ Number', $delivery_list) !!}
            </div>
        </div>
         <a class="button" href="{{route($indexRoute)}}">Back</a>
        <button type="submit" class="button">Save</button>

    </div>
</div>

{!!Form::close()!!}
@stop
@section('scripts')

<script type="text/javascript">
    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-purchase_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    // end datepicker
</script>
@stop