@section('title')
Purchase Order
@stop

@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
@stop

@section('modal')
    @include('modules.partials.modals.delete')
@stop

@section('contents')


<div class="row">
    <div class="twelve columns utility utility--align-right" >
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button type="submit" class="button"><i class="nc-icon-mini ui-2_disk"></i></button>

        <a href="#" class="button topbar__utility__button--modal" tooltip="Delete"><i class="nc-icon-mini ui-1_trash"></i></a>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        {!! Form::model($data, $modelConfig['update']) !!}


            <div class="row">
                <div class="six columns">
                    {!! Form::selectField('rfq_id', 'RFQ Number', $rfq_list) !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('purchase_date', 'Purchase Date') !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::selectField('payment_term', 'Payment Terms', $term_lists) !!}
                </div>
            </div>

            <div class="row">

                <div class="six columns align-right">
                    <button class="button topbar__utility__button--modal" >Delete</button>
                </div>

            </div>
        {!! Form::close() !!}
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
    // pickmeup('#id-field-transaction_date', {
    //     format  : 'Y-m-d',
    //     default_date: false
    // });

    // pickmeup('#id-field-deadline', {
    //     format  : 'Y-m-d',
    //     default_date: false
    // });

    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-transaction_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });


    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-deadline'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    // end datepicker
</script>
@stop