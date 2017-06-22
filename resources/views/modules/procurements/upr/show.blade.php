@section('title')
Unit Purchase Request
@stop

@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
@stop

@section('modal')
    @include('modules.partials.modals.request_quotation')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns align-right utility utility--align-right">

        <button class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                 @if($data->status == 'pending')
                    <a class="button__options__item topbar__utility__button--modal" href="#">PROCESS</a>
                @endif

                @if(count($data->rfq) != 0)
                    <a href="{{route('procurements.blank-rfq.show', $data->rfq->id)}}" class="button__options__item">Request for Quotation</a>
                @endif

                @if(count($data->philgeps) != 0)
                    <a href="{{route('procurements.philgeps-posting.edit', $data->philgeps->id)}}" class="button__options__item">PhilGeps</a>
                @endif

                @if(count($data->canvassing) != 0)
                    <a href="{{route('procurements.canvassing.show', $data->canvassing->id)}}" class="button__options__item">Canvassing</a>
                    <a href="{{route('procurements.noa.show', $data->canvassing->id)}}" class="button__options__item">Notice of Award</a>
                @endif

                @if(count($data->purchase_order) != 0)
                    <a href="{{route('procurements.purchase-orders.show', $data->purchase_order->id)}}" class="button__options__item">Purchase Order</a>
                    <a href="{{route('procurements.ntp.show', $data->purchase_order->id)}}" class="button__options__item">Notice to Proceed</a>
                @endif

                @if(count($data->delivery_order) != 0)
                    <a href="{{route('procurements.delivery-orders.show', $data->delivery_order->id)}}" class="button__options__item">Delivery</a>
                @endif
            </div>
        </button>

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
<br>
<br>
<div class="row">
    <div class="six columns pull-left">
        <ul>
            <li> <strong>UPR No. :</strong> {{$data->upr_number}} </li>
            <li> <strong>AFPPS No. :</strong> {{$data->afpps_ref_number}} </li>
            <li> <strong>Date Prepared :</strong> {{$data->date_prepared}} </li>
            <li> <strong>Place of delivery :</strong> {{($data->centers) ? $data->centers->name :""}} </li>
            <li> <strong>Mode of Procurement :</strong> {{($data->modes) ? $data->modes->name :""}} </li>
            <li> <strong>Units :</strong> {{($data->unit) ? $data->unit->name :""}} </li>
            <li> <strong>Total ABC :</strong> {{number_format($data->total_amount,2)}} </li>
        </ul>
    </div>
    <div class="six columns">
        <ul>
            <li> <strong>Chargeability :</strong> {{($data->charges) ? $data->charges->name :""}} </li>
            <li> <strong>Account Code :</strong> {{($data->accounts) ? $data->accounts->new_account_code :""}} </li>
            <li> <strong>Fund Validity :</strong> {{$data->fund_validity}} </li>
            <li> <strong>Terms of Payment :</strong> {{($data->terms) ? $data->terms->name :""}} </li>
            <li> <strong>Prepared by :</strong> {{($data->users) ? $data->users->first_name ." ". $data->users->surname :""}} </li>
            <li> <strong>Status :</strong> {{ucfirst($data->status)}} </li>
            @if($data->date_processed)
            <li> <strong>Date Processed :</strong> {{$data->date_processed}} </li>
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
        <h3>Items</h3>
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
                        <td>{{$item->total_amount}}</td>
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

    var timepicker = new TimePicker('id-field-opening_time', {
        lang: 'en',
        theme: 'dark'
    });

    timepicker.on('change', function(evt){
      var value = (evt.hour || '00') + ':' + (evt.minute || '00');
      evt.element.value = value;
    });

    // // datepicker
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
</script>
@stop