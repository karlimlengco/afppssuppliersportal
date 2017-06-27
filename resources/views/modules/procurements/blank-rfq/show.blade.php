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
    @include('modules.partials.modals.invitation')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <button class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                @if($data->status != 'closed')
                <a href="#" class="button__options__item" id="proponent-button">Add Proponents</a>
                @endif
                @if($data->status == 'pending')
                    <a href="#" class="button__options__item" id="philgeps-posting-button">PhilGeps Posting</a>
                @else
                    @if($data->status != 'closed')
                    <a href="#" class="button__options__item" id="close-button">Close RFQ</a>
                    @else

                        @if(count($data->invitations) == 0)
                            <a href="#" class="button__options__item" id="invitation-button">Create Invitation</a>
                        @endif
                    @endif
                @endif

                <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" class=" button__options__item">Unit Purchase Request</a>

                @if(count($data->philgeps) != 0)
                    <a href="{{route('procurements.philgeps-posting.show', $data->philgeps->id)}}" class="button__options__item">PhilGeps Posting</a>
                @endif

                @if(count($data->invitations) != 0)
                    <a href="{{route('procurements.ispq.edit', $data->invitations->ispq_id)}}" class="button__options__item">Invitation</a>


                    @if(count($data->canvassing) == 0)
                        <a href="{{route('procurements.canvassing.opening', $data->id)}}" class="button__options__item">Open Canvass</a>
                    @endif
                @endif

                @if(count($data->canvassing) != 0)
                    <a href="{{route('procurements.canvassing.show', $data->canvassing->id)}}" class="button__options__item">View Canvass</a>
                @endif

                @if(count($data->noa) != 0)
                    <a href="{{route('procurements.noa.show', $data->noa->id)}}" class="button__options__item">View NOA</a>
                    @if(count($data->po) == 0)
                        <a href="{{route('procurements.purchase-orders.rfq', $data->id)}}" class="button__options__item">Create PO</a>
                    @else
                        <a href="{{route('procurements.purchase-orders.show', $data->id)}}" class="button__options__item">View PO</a>
                    @endif
                @endif

                @if(count($data->ntp) != 0)
                    <a href="{{route('procurements.ntp.show', $data->ntp->id)}}" class="button__options__item">View NTP</a>
                @endif

                @if(count($data->delivery) != 0)
                    <a href="{{route('procurements.delivery-orders.show', $data->delivery->id)}}" class="button__options__item">View Delivery</a>
                    <a href="{{route('procurements.delivery-to-coa.show', $data->delivery->id)}}" class="button__options__item">View Delivery To COA</a>
                @endif
                @if(count($data->diir) != 0)
                    <a href="{{route('procurements.delivered-inspections.show', $data->diir->id)}}" class="button__options__item">View DIIR</a>

                @endif

            </div>
        </button>

        <a target="_blank" href="{{route($printRoute,$data->id)}}" class="button" tooltip="Print">
            <i class="nc-icon-mini tech_print"></i>
        </a>
        @if($data->status != 'closed')
            <a href="{{route($editRoute,$data->id)}}" class="button" tooltip="Edit">
                <i class="nc-icon-mini design_pen-01"></i>
            </a>
        @endif

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
            @endif
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
                    <th>Notes</th>
                    <th>Prepared By</th>
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

                        @if($data->status != 'closed')
                        <a href="{{route('procurements.rfq-proponents.delete',$proponent->id)}}" tooltip="remove"> <span class="nc-icon-glyph ui-1_trash-simple"></span> </a>
                        @endif
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
    var timepicker1 = new TimePicker(['id-field-opening_time', 'id-field-canvassing_time'], {
        lang: 'en',
        theme: 'dark'
    });

    timepicker1.on('change', function(evt){
      var value = (evt.hour || '00') + ':' + (evt.minute || '00');
      evt.element.value = value;
    });

    $('#proponent-button').click(function(e){
        e.preventDefault();
        $('#proponent-modal').addClass('is-visible');
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