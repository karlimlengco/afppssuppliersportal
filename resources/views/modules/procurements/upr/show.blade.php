@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
@stop
@section('modal')
    @include('modules.partials.modals.request_quotation')
@stop

@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3>Unit Purchase Request</h3>
    </div>
    <div class="six columns align-right">
        @if($data->status == 'pending')
        <button class="button topbar__utility__button--modal">Process</button>
        @endif
        <a class="button" href="{{route($indexRoute)}}">Back</a>
        <a class="button" href="{{route($editRoute,$data->id)}}">Edit</a>
    </div>
</div>

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
    <div class="six columns pull-right">
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