@section('modal')
    @include('modules.partials.modals.ntp_received')
    @include('modules.partials.modals.create_delivery')
@stop

@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3>Notice To Proceed</h3>
    </div>
    <div class="six columns align-right">
        @if($data->pcco_released_date and $data->mfo_released_date)
            @if(!$data->received_by)
            <a class="button" id="proceed-ntp-button" href="#">Received</a>
            @else
                @if(count($data->delivery) == 0)
                    <a class="button" id="create-delivery-button" href="#">Create Notice Of Delivery</a>
                @endif
            @endif
            <a class="button" href="#">Print</a>
        @endif
        <a class="button" href="{{route($indexRoute,$data->rfq_id)}}">Back</a>
    </div>
</div>

<div class="row">
    <div class="four columns pull-left">
        <h3>Purchase Details</h3>
        <ul>
            <li> <strong>Purchase Date :</strong> {{$data->purchase_date}} </li>
            <li> <strong>Bid Amount :</strong> {{$data->bid_amount}} </li>
            <li> <strong>Payment Term :</strong> {{($data->terms) ? $data->terms->name : ""}} </li>
            <li> <strong>Prepared By :</strong> {{($data->users) ? $data->users->first_name ." ". $data->users->surname : ""}} </li>
            <li> <strong>Status :</strong> {{$data->status}} </li>
        </ul>
    </div>
    <div class="four columns">
        <h3>Approval Details</h3>
        <ul>
            <li> <strong>MFO Has Issue? :</strong> {{$data->mfo_has_issue}} </li>
            <li> <strong>MFO Released Date :</strong> {{$data->mfo_released_date}} </li>
            <li> <strong>MFO Received Date :</strong> {{$data->mfo_received_date}} </li>
            <li> <strong>MFO Remarks :</strong> {{$data->mfo_remarks}} </li>
            <li> <strong>PCCO Has Issue? :</strong> {{$data->pcco_has_issue}} </li>
            <li> <strong>PCCO Released Date :</strong> {{$data->pcco_released_date}} </li>
            <li> <strong>PCCO Received Date :</strong> {{$data->pcco_received_date}} </li>
            <li> <strong>PCCO Remarks :</strong> {{$data->pcco_remarks}} </li>
        </ul>
    </div>
    <div class="four columns pull-right">
        <h3>Proponent Details</h3>
        <ul>
            <li> <strong>Name :</strong> {{$supplier->name}} </li>
            <li> <strong>Owner :</strong> {{$supplier->owner}} </li>
            <li> <strong>Address :</strong> {{$supplier->address}} </li>
            <li> <strong>TIN :</strong> {{$supplier->tin}} </li>
            <li> <strong>Cellphone # :</strong> {{$supplier->cell_1}} </li>
            <li> <strong>Phone # :</strong> {{$supplier->phone_1}} </li>
            <li> <strong>FAX :</strong> {{$supplier->fax_1}} </li>
            <li> <strong>Email :</strong> {{$supplier->email_1}} </li>
        </ul>
    </div>
</div>
<hr>
<br>
<div class="row">
    <div class="six columns pull-left">
        <h3>UPR Details</h3>
        <ul>
            <li> <strong>UPR No. :</strong> {{$upr_model->upr_number}} </li>
            <li> <strong>AFPPS No. :</strong> {{$upr_model->afpps_ref_number}} </li>
            <li> <strong>Date Prepared :</strong> {{$upr_model->date_prepared}} </li>
            <li> <strong>Place of delivery :</strong> {{($upr_model->centers) ? $upr_model->centers->name :""}} </li>
            <li> <strong>Mode of Procurement :</strong> {{($upr_model->modes) ? $upr_model->modes->name :""}} </li>
            <li> <strong>Units :</strong> {{($upr_model->unit) ? $upr_model->unit->name :""}} </li>
            <li> <strong>Total ABC :</strong> {{number_format($upr_model->total_amount,2)}} </li>
        </ul>
    </div>
    <div class="six columns pull-right">
        <h3> </h3>
        <ul>
            <li> <strong>Chargeability :</strong> {{($upr_model->charges) ? $upr_model->charges->name :""}} </li>
            <li> <strong>Account Code :</strong> {{($upr_model->accounts) ? $upr_model->accounts->new_account_code :""}} </li>
            <li> <strong>Fund Validity :</strong> {{$upr_model->fund_validity}} </li>
            <li> <strong>Terms of Payment :</strong> {{($upr_model->terms) ? $upr_model->terms->name :""}} </li>
            <li> <strong>Prepared by :</strong> {{($upr_model->users) ? $upr_model->users->first_name ." ". $upr_model->users->surname :""}} </li>
            <li> <strong>Status :</strong> {{ucfirst($upr_model->status)}} </li>
            @if($upr_model->date_processed)
            <li> <strong>Date Processed :</strong> {{$upr_model->date_processed}} </li>
            @endif
        </ul>
    </div>
</div>
<hr>
<br>
<div class="row">
    <div class="twelve columns ">
        <h3>Items Details</h3>
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
                        <td>{{$item->description}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->unit}}</td>
                        <td>{{formatPrice($item->price_unit)}}</td>
                        <td>{{formatPrice($item->total_amount)}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop

@section('scripts')
<script type="text/javascript">


$('#proceed-ntp-button').click(function(e){
    e.preventDefault();
    $('#proceed-ntp-modal').addClass('is-visible');
})

$('#create-delivery-button').click(function(e){
    e.preventDefault();
    $('#create-delivery-modal').addClass('is-visible');
})


var award_accepted_date = new Pikaday(
{
    field: document.getElementById('id-field-award_accepted_date'),
    firstDay: 1,
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});


var transaction_date = new Pikaday(
{
    field: document.getElementById('id-field-transaction_date'),
    firstDay: 1,
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});

var expected_date = new Pikaday(
{
    field: document.getElementById('id-field-expected_date'),
    firstDay: 1,
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});


</script>
@stop