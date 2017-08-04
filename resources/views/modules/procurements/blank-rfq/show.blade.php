@section('title')
Request For Quotation
@stop

@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
@stop

@section('modal')
    @include('modules.partials.modals.proponents')
    @include('modules.partials.modals.philgeps_posting')
    @include('modules.partials.modals.close_rfq')
    {{-- @include('modules.partials.modals.invitation') --}}
    @include('modules.partials.modals.open_canvass')
@stop

@section('breadcrumbs')

    @if(isset($breadcrumbs))
      @foreach($breadcrumbs as $route => $crumb)
        @if($crumb->hasLink())
        <a href="{{ $crumb->link() }}" class="topbar__breadcrumbs__item">{{ $crumb->title() }}</a>
        @else
        <a href="#" class="topbar__breadcrumbs__item">{{ $crumb->title() }}</a>
        @endif
      @endforeach
    @else
    <li><a href="#">Application</a></li>
    @endif

@stop


@section('contents')

<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <button class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                {{-- @if($data->status != 'closed') --}}
                <a href="#" class="button__options__item" id="proponent-button">Add Proponents</a>
                {{-- @endif --}}

                @if($data->status != 'closed')
                    <a href="#" class="button__options__item" id="close-button">Close RFQ</a>
                @endif

                <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" class=" button__options__item">Unit Purchase Request</a>

            </div>
        </button>

        <a target="_blank" href="{{route($printRoute,$data->id)}}" class="button" tooltip="Print">
            <i class="nc-icon-mini tech_print"></i>
        </a>


        <a href="{{route('procurements.blank-rfq.logs', $data->id)}}" class="button" tooltip="Logs">
            <i class="nc-icon-mini files_archive-content"></i>
        </a>

        {{-- @if($data->status != 'closed') --}}
            <a href="{{route($editRoute,$data->id)}}" class="button" tooltip="Edit">
                <i class="nc-icon-mini design_pen-01"></i>
            </a>
        {{-- @endif --}}

        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

    </div>
</div>

<div class="data-panel">
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">RFQ No. :</strong> {{$data->rfq_number}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">UPR No. :</strong> {{$data->upr_number}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Status :</strong> {{ ucfirst($data->status) }} </li>
            @if($data->status == 'closed')
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Closed At :</strong> {{ $data->completed_at }} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Closed Remarks :</strong> {{ $data->close_remarks }} </li>
            @endif
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Working Days :</strong> {{ $data->days }} </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">ABC :</strong> {{ formatPrice($data->upr->total_amount) }} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Deadline to Submit :</strong> {{ $data->deadline }} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Canvas Opening Time :</strong> {{ $data->opening_time }} </li>
    </div>
    <div class="data-panel__section">
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">TransactionDate :</strong> {{ $data->transaction_date }} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Remarks :</strong> {{ $data->remarks }} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Processed By :</strong> {{($data->processor) ? $data->processor->first_name ." ". $data->processor->surname :""}} </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="six columns">
        <h3>Proponents</h3>
    </div>
</div>

@if(count($data->proponents) != 0)
<div class="row">
    <div class="twelve columns">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Processed Date</th>
                    <th>Bid Amount</th>
                    <th>Prepared By</th>
                    <th>Notes</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->proponents as $proponent)
                <tr>
                    <td>{{($proponent->supplier) ? $proponent->supplier->name :""}}</td>
                    <td>{{$proponent->date_processed}}</td>
                    <td>{{formatPrice($proponent->bid_amount)}}</td>
                    <td>{{($proponent->users) ? $proponent->users->first_name ." ". $proponent->users->surname :""}} </td>
                    <td>{{$proponent->note}}</td>
                    <td>
                        <a href="{{route('procurements.rfq-proponents.show',$proponent->id)}}" tooltip="attachments"> <span class="nc-icon-glyph ui-1_attach-87"></span> </a>

                        {{-- @if($data->status != 'closed') --}}
                        <a href="{{route('procurements.rfq-proponents.delete',$proponent->id)}}" tooltip="remove"> <span class="nc-icon-glyph ui-1_trash-simple"></span> </a>
                        {{-- @endif --}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@stop

@section('scripts')
<script src="/vendors/timepicker/timepicker.min.js"></script>
<script type="text/javascript">
    // var timepicker1 = new TimePicker(['id-field-opening_time', 'id-field-canvassing_time'], {
    //     lang: 'en',
    //     theme: 'dark'
    // });

    // timepicker1.on('change', function(evt){
    //   var value = (evt.hour || '00') + ':' + (evt.minute || '00');
    //   evt.element.value = value;
    // });

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
    // var date_processed = new Pikaday(
    // {
    //     field: document.getElementById('id-field-date_processed'),
    //     firstDay: 1,
    //     // minDate: new Date(),
    //     maxDate: new Date(2020, 12, 31),
    //     yearRange: [2000,2020]
    // });

    // var completed_at = new Pikaday(
    // {
    //     field: document.getElementById('id-field-completed_at'),
    //     firstDay: 1,
    //     defaultDate: new Date(),
    //     setDefaultDate: new Date(),
    //     // minDate: new Date(),
    //     maxDate: new Date(2020, 12, 31),
    //     yearRange: [2000,2020]
    // });


    // // datepicker
    // var transaction_date = new Pikaday(
    // {
    //     field: document.getElementById('id-field-transaction_date'),
    //     firstDay: 1,
    //     defaultDate: new Date(),
    //     setDefaultDate: new Date(),
    //     // minDate: new Date(),
    //     maxDate: new Date(2020, 12, 31),
    //     yearRange: [2000,2020]
    // });


    // // datepicker
    // var ispq_transaction_dates = new Pikaday(
    // {
    //     field: document.getElementById('id-field-ispq_transaction_dates'),
    //     firstDay: 1,
    //     defaultDate: new Date(),
    //     setDefaultDate: new Date(),
    //     // minDate: new Date(),
    //     maxDate: new Date(2020, 12, 31),
    //     yearRange: [2000,2020]
    // });

    // // datepicker
    // var open_canvass_date = new Pikaday(
    // {
    //     field: document.getElementById('id-field-open_canvass_date'),
    //     firstDay: 1,
    //     defaultDate: new Date(),
    //     setDefaultDate: new Date(),
    //     // minDate: new Date(),
    //     maxDate: new Date(2020, 12, 31),
    //     yearRange: [2000,2020]
    // });

    // var deadline_rfq = new Pikaday(
    // {
    //     field: document.getElementById('id-field-deadline_rfq'),
    //     firstDay: 1,
    //     // minDate: new Date(),
    //     maxDate: new Date(2020, 12, 31),
    //     yearRange: [2000,2020]
    // });
    // var canvassing_date = new Pikaday(
    // {
    //     field: document.getElementById('id-field-canvassing_date'),
    //     firstDay: 1,
    //     // minDate: new Date(),
    //     maxDate: new Date(2020, 12, 31),
    //     yearRange: [2000,2020]
    // });
    // var philgeps_posting = new Pikaday(
    // {
    //     field: document.getElementById('id-field-philgeps_posting'),
    //     firstDay: 1,
    //     // minDate: new Date(),
    //     maxDate: new Date(2020, 12, 31),
    //     yearRange: [2000,2020]
    // });

</script>
@stop