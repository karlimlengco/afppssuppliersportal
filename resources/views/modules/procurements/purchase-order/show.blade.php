@section('title')
Purchase Order
@stop

@section('modal')
    @include('modules.partials.modals.mfo')
    @include('modules.partials.modals.pcco')
@stop

@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3> </h3>
    </div>
    <div class="six columns align-right">

        <a class="button" href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="UPR"> <span class="nc-icon-glyph business_agenda"></span> </a>
        <a href="{{route('procurements.blank-rfq.show', $data->rfq_id)}}" class="button" tooltip="RFQ"> <span class=" nc-icon-glyph ui-1_edit-74"></span> </a>
        <a href="{{route('procurements.ntp.show', $data->id)}}" class="button" tooltip="NTP"> NTP</a>

        @if(count($data->delivery) != 0)
        <a href="{{route('procurements.delivery-orders.show', $data->delivery   ->id)}}" class="button" tooltip="DELIVERY"> <span class=" nc-icon-glyph transportation_truck-front"></span>  </a>
        @endif

        @if(!$data->mfo_released_date)
        <a class="button " id="mfo-button" href="#">MFO Approval</a>
        @endif
        @if(!$data->pcco_released_date)
        <a class="button" id="pcco-button" href="#">PCCO Approval</a>
        @endif

        @if($data->pcco_released_date and $data->mfo_released_date)
            <a class="button" href="#">Print</a>
        @endif
        <a class="button" href="{{route($indexRoute,$data->rfq_id)}}">Back</a>
    </div>
</div>

<div class="row">
    <div class="four columns pull-left">
        <h3>Purchase Details</h3>
        <ul>
            <li> <strong>Purchase Number :</strong> {{$data->po_number}} </li>
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
<script src="/js/dropzone.js"></script>
<script type="text/javascript">

// show modal
$('#pcco-button').click(function(e){
    e.preventDefault();
    $('#pcco-modal').addClass('is-visible');
})
$('#mfo-button').click(function(e){
    e.preventDefault();
    $('#mfo-modal').addClass('is-visible');
})

$('#proceed-ntp-button').click(function(e){
    e.preventDefault();
    $('#proceed-ntp-modal').addClass('is-visible');
})


var mfo_released_date = new Pikaday(
{
    field: document.getElementById('id-field-mfo_released_date'),
    firstDay: 1,
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});


var mfo_received_date = new Pikaday(
{
    field: document.getElementById('id-field-mfo_received_date'),
    firstDay: 1,
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});


var pcco_released_date = new Pikaday(
{
    field: document.getElementById('id-field-pcco_released_date'),
    firstDay: 1,
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});

var pcco_received_date = new Pikaday(
{
    field: document.getElementById('id-field-pcco_received_date'),
    firstDay: 1,
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});

</script>
@stop