@section('title')
Request For Quotation
@stop

@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
@stop

@section('modal')
    @include('modules.partials.modals.proponents')
    @include('modules.partials.modals.philgeps_posting')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <button class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                <a href="#" class="button__options__item" id="proponent-button">Add Proponents</a>
                @if($data->status == 'pending')
                    <a href="#" class="button__options__item" id="philgeps-posting-button">PhilGeps Posting</a>
                @endif

                @if(count($data->philgeps) != 0)
                    <a href="{{route('procurements.philgeps-posting.show', $data->philgeps->id)}}" class="button__options__item">PhilGeps Posting</a>
                @endif

                <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" class=" button__options__item">Unit Purchase Request</a>

                @if(count($data->canvassing))
                    <a href="{{route('procurements.canvassing.show', $data->canvassing->id)}}" class=" button__options__item">Canvassing</a>
                    <a href="{{route('procurements.noa.show', $data->canvassing->id)}}" class=" button__options__item">Awardee</a>
                @endif
            </div>
        </button>

        <a target="_blank" href="{{route($printRoute,$data->id)}}" class="button" tooltip="Print">
            <i class="nc-icon-mini tech_print"></i>
        </a>
        <a href="{{route($editRoute,$data->id)}}" class="button" tooltip="Edit">
            <i class="nc-icon-mini design_pen-01"></i>
        </a>

        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

    </div>
</div>
<br>
<div class="row">
    <div class="six columns pull-left">
        <ul>
            <li> <strong>RFQ No. :</strong> {{$data->rfq_number}} </li>
            <li> <strong>UPR No. :</strong> {{$data->upr_number}} </li>
            <li> <strong>Status :</strong> {{ ucfirst($data->status) }} </li>
            <li> <strong>ABC :</strong> {{ formatPrice($data->upr->total_amount) }} </li>
        </ul>
    </div>
    <div class="six columns pull-right">
        <ul>
            <li> <strong>Deadline to Submit :</strong> {{ $data->deadline }} </li>
            <li> <strong>Canvas Opening Time :</strong> {{ $data->opening_time }} </li>
            <li> <strong>TransactionDate :</strong> {{ $data->transaction_date }} </li>
            <li> <strong>Remarks :</strong> {{ $data->remarks }} </li>
            <li> <strong>Processed By :</strong> {{($data->processor) ? $data->processor->first_name ." ". $data->processor->surname :""}} </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="six columns">
        <h3>Proponents</h3>
    </div>
    <div class="six columns">
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
                        <a href="{{route('procurements.rfq-proponents.delete',$proponent->id)}}" tooltip="remove"> <span class="nc-icon-glyph ui-1_trash-simple"></span> </a>
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
    var timepicker = new TimePicker('id-field-opening_time', {
        lang: 'en',
        theme: 'dark'
    });

    timepicker.on('change', function(evt){
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
    // datepicker
    // pickmeup('#id-field-date_processed', {
    //     format  : 'Y-m-d'
    // });
    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-date_processed'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });


    // datepicker
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
        field: document.getElementById('id-field-deadline_rfq'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-philgeps_posting'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

</script>
@stop