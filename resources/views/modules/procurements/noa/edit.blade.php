@section('title')
Notice Of Award
@stop

@section('modal')
    {!! Form::model($data, $modelConfig['update']) !!}
    @include('modules.partials.modals.edit-remarks')
@stop

@section('contents')


<div class="row">
    <div class="twelve columns align-right utility utility--align-right">

            <a href="{{route($indexRoute, $data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
            <button type="button" class="button" id="edit-button" ><i class="nc-icon-mini ui-2_disk"></i></button>
    </div>
</div>

<div class="row">
    <div class="twelve columns">

            <div class="row">
                <div class="four columns">
                    {!! Form::textField('awarded_date', 'Award Date') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('award_accepted_date', 'Received Date') !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('accepted_date', 'Approved Date') !!}
                </div>
            </div>

        {!! Form::close() !!}
    </div>
</div>


@stop

@section('scripts')

<script type="text/javascript">


    $('#edit-button').click(function(e){
        e.preventDefault();
        $('#edit-modal').addClass('is-visible');
    })

    // datepicker

    var awarded_date = new Pikaday(
    {
        field: document.getElementById('id-field-awarded_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    var award_accepted_date = new Pikaday(
    {
        field: document.getElementById('id-field-award_accepted_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    var accepted_date = new Pikaday(
    {
        field: document.getElementById('id-field-accepted_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    // end datepicker
</script>
@stop