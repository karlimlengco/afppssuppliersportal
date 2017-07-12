@section('title')
NOA Acceptance
@stop

@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
@stop

@section('modal')
@stop

@section('contents')
<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <button class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">


            </div>
        </button>

        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

    </div>
</div>

<div class="data-panel">
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">UPR No. :</strong> {{$data->upr_number}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Supplier. :</strong> {{$data->supplier->name}} </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Received Date :</strong> {{ $data->received_noa }} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Received By :</strong> {{ $data->received_by }} </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul>
            {{-- <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">TransactionDate :</strong> {{ $data->transaction_date }} </li> --}}
        </ul>
    </div>
</div>

@stop

@section('scripts')
<script src="/vendors/timepicker/timepicker.min.js"></script>
<script type="text/javascript">

    $('#proponent-button').click(function(e){
        e.preventDefault();
        $('#proponent-modal').addClass('is-visible');
    })
    $('#open_canvass-button').click(function(e){
        e.preventDefault();
        $('#open_canvass-modal').addClass('is-visible');
    })
    $('#philgeps-posting-button').click(function(e){
        e.preventDefault();
        $('#philgeps-posting-modal').addClass('is-visible');
    })
    $('#close-button').click(function(e){
        e.preventDefault();
        $('#close-modal').addClass('is-visible');
    })
    $('#invitation-button').click(function(e){
        e.preventDefault();
        $('#invitation-modal').addClass('is-visible');
    })
    // datepicker
    // pickmeup('#id-field-date_processed', {
    //     format  : 'Y-m-d'
    // });
    var date_processed = new Pikaday(
    {
        field: document.getElementById('id-field-date_processed'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });


    // datepicker
    var transaction_date = new Pikaday(
    {
        field: document.getElementById('id-field-transaction_date'),
        firstDay: 1,
        defaultDate: new Date(),
        setDefaultDate: new Date(),
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });


    // datepicker
    var ispq_transaction_dates = new Pikaday(
    {
        field: document.getElementById('id-field-ispq_transaction_dates'),
        firstDay: 1,
        defaultDate: new Date(),
        setDefaultDate: new Date(),
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    // datepicker
    var open_canvass_date = new Pikaday(
    {
        field: document.getElementById('id-field-open_canvass_date'),
        firstDay: 1,
        defaultDate: new Date(),
        setDefaultDate: new Date(),
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    var deadline_rfq = new Pikaday(
    {
        field: document.getElementById('id-field-deadline_rfq'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    var canvassing_date = new Pikaday(
    {
        field: document.getElementById('id-field-canvassing_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    var philgeps_posting = new Pikaday(
    {
        field: document.getElementById('id-field-philgeps_posting'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

</script>
@stop