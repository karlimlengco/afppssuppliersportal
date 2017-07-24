@section('title')
Notice Of Delivery
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
                    {!! Form::textField('expected_date', 'Expexted Date') !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('delivery_date', 'Delivery Date') !!}
                </div>
            </div>

            <div class="row">

                <div class="six columns">
                        {!! Form::textField('transaction_date', 'Transaction Date') !!}
                </div>
                <div class="six columns">
                        {!! Form::textField('date_delivered_to_coa', 'COA Delivery Date') !!}
                </div>
            </div>

        </div>
    </div>


{!! Form::close() !!}

@stop

@section('scripts')
<script src="/vendors/timepicker/timepicker.min.js"></script>

<script type="text/javascript">


    $('#edit-button').click(function(e){
        e.preventDefault();
        $('#edit-modal').addClass('is-visible');
    })
    // datepicker

     var expected_date = new Pikaday(
    {
        field: document.getElementById('id-field-expected_date'),
        firstDay: 1,
    });

     var delivery_date = new Pikaday(
    {
        field: document.getElementById('id-field-delivery_date'),
        firstDay: 1,
    });

     var transaction_date = new Pikaday(
    {
        field: document.getElementById('id-field-transaction_date'),
        firstDay: 1,
    });

     var date_delivered_to_coa = new Pikaday(
    {
        field: document.getElementById('id-field-date_delivered_to_coa'),
        firstDay: 1,
    });

    // end datepicker
</script>
@stop