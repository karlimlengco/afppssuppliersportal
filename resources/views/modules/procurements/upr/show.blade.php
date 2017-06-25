@section('title')
Unit Purchase Request
@stop

@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
@stop

@section('modal')
    @include('modules.partials.modals.view-attachments')
    @include('modules.partials.modals.request_quotation')
    @include('modules.partials.modals.dropzone')
@stop

@section('contents')
<div class="row">
    <div class="twelve columns align-right utility utility--align-right">

        <button class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                <a class="button__options__item" id="view-attachments-button" href="#">View Attachments</a>
                <a class="button__options__item" href="{{route('procurements.unit-purchase-requests.timelines', $data->id)}}">View Timelines</a>
                @if($data->status == 'pending')
                    <a class="button__options__item" id="process-button" href="#">Process</a>
                @endif

                @if(count($data->rfq) != 0)
                    <a href="{{route('procurements.blank-rfq.show', $data->rfq->id)}}" class="button__options__item">Request for Quotation</a>
                @endif
            </div>
        </button>

        <a href="#" id="attachment-button" class="button" tooltip="Attachments">
            <i class="nc-icon-mini ui-1_attach-86"></i>
        </a>

        <a href="#" class="button" tooltip="Print">
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
            <li> <strong>Project Name:</strong> {{$data->project_name}} </li>
            <li> <strong>UPR No. :</strong> {{$data->upr_number}} </li>
            <li> <strong>Reference No. :</strong> {{$data->ref_number}} </li>
            <li> <strong>Date Prepared :</strong> {{$data->date_prepared}} </li>
            <li> <strong>Prepared by :</strong> {{($data->users) ? $data->users->first_name ." ". $data->users->surname :""}} </li>
            <li> <strong>Place of delivery :</strong> {{($data->centers) ? $data->centers->name :""}} </li>
            <li> <strong>Mode of Procurement :</strong> {{($data->modes) ? $data->modes->name :""}} </li>
            <li> <strong>Total ABC :</strong> {{number_format($data->total_amount,2)}} </li>
            <li> <strong>Created :</strong> {{$data->created_at}} </li>
        </ul>
    </div>
    <div class="six columns">
        <ul>
            <li> <strong>Units :</strong>    {{($data->unit) ? $data->unit->name :""}} </li>
            <li> <strong>Chargeability :</strong> {{($data->charges) ? $data->charges->name :""}} </li>
            <li> <strong>Account Code :</strong> {{($data->accounts) ? $data->accounts->new_account_code :""}} </li>
            <li> <strong>Fund Validity :</strong> {{$data->fund_validity}} </li>
            <li> <strong>Terms of Payment :</strong> {{($data->terms) ? $data->terms->name :""}} </li>
            <li> <strong>Status :</strong> {{ucfirst($data->status)}} </li>
            <li> <strong>State :</strong> {{ucfirst($data->state)}} </li>
            <li> <strong>Total ABC :</strong> {{number_format($data->total_amount,2)}} </li>
            @if($data->date_processed)
            <li> <strong>Date Processed :</strong> {{$data->date_processed}} </li>
            <li> <strong>Processed By :</strong> {{($data->processor) ? $data->processor->first_name ." ". $data->processor->surname :""}} </li>
            @endif
        </ul>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        <ul>
            <li> <strong>Purpose of Purchase :</strong>
                <ul>
                    <li> {{$data->purpose}} </li>
                </ul>
            </li>
            <li> <strong>Other Essential Info :</strong>
                <ul>
                    <li> {{$data->other_infos}} </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="twelve columns">
        <table class='table' id="item_table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->items as $item)
                    <tr>
                        <td>{{$item->item_description}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->unit_measurement}}</td>
                        <td>{{$item->unit_price}}</td>
                        <td>{{formatPrice($item->total_amount)}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('scripts')
<script src="/vendors/timepicker/timepicker.min.js"></script>
<script type="text/javascript">

    $('#attachment-button').click(function(e){
        e.preventDefault();
        $('#dropzone-modal').addClass('is-visible');
    })

    $('#process-button').click(function(e){
        e.preventDefault();
        $('#process-modal').addClass('is-visible');
    })

    $('#view-attachments-button').click(function(e){
        e.preventDefault();
        $('#view-attachments-modal').addClass('is-visible');
    })

    var timepicker = new TimePicker('id-field-opening_time', {
        lang: 'en',
        theme: 'dark'
    });

    timepicker.on('change', function(evt){
      var value = (evt.hour || '00') + ':' + (evt.minute || '00');
      evt.element.value = value;
    });

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
</script>
@stop