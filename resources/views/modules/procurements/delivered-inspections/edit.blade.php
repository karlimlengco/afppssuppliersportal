@section('title')
Delivered Items Inspection
@stop

@section('modal')
    {!! Form::model($data, $modelConfig['update']) !!}
    @include('modules.partials.modals.edit-remarks')
@stop

@section('contents')

    <div class="row">
        <div class="twelve columns align-right utility utility--align-right">

            <button type="button" class="button"  id="edit-button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

            <a href="{{route($showRoute, $data->id)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

        </div>
    </div>
    <div class="row">
        <div class="twelve columns">

            <div class="row">
                <div class="six columns">
                    {!! Form::textField('start_date', 'Start Date') !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('closed_date', 'Closed Date') !!}
                </div>
            </div>


        </div>
    </div>


{!! Form::close() !!}

@stop

@section('scripts')

<script type="text/javascript">


    $('#edit-button').click(function(e){
        e.preventDefault();
        $('#edit-modal').addClass('is-visible');
    })
    // datepicker

     var start_date = new Pikaday(
    {
        field: document.getElementById('id-field-start_date'),
        firstDay: 1,
    });

     var closed_date = new Pikaday(
    {
        field: document.getElementById('id-field-closed_date'),
        firstDay: 1,
    });

    // end datepicker
</script>
@stop