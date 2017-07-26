@section('title')
Notice To Proceed
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

@section('modal')
    @include('modules.partials.modals.signatory')
    @include('modules.partials.modals.ntp_received')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns utility utility--align-right">
        <button class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                <a id="signatory-button" href="#" class="button__options__item" > Signatories</a>

                @if(!$data->received_by)
                <a class="button__options__item" id="proceed-ntp-button" href="#">Received</a>
                @else
                   {{--  @if(count($data->delivery) == 0)
                        <a class="button__options__item" id="create-delivery-button" href="#">Create Notice Of Delivery</a>
                    @else
                        <a class="button__options__item"  href="{{route('procurements.delivery-orders.show', $data->delivery->id)}}">View Notice Of Delivery</a>
                    @endif --}}
                @endif
                <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" class="button__options__item" tooltip="UPR"> Unit Purchase Request</a>
                {{-- <a href="{{route('procurements.blank-rfq.show', $data->rfq_id)}}" class="button__options__item" tooltip="UPR"> Request For Quotation</a> --}}
                {{-- <a href="{{route('procurements.purchase-orders.show', $data->po_id)}}" class="button__options__item" tooltip="UPR"> Purchase Order</a> --}}



            </div>
        </button>
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>


        <a target="_blank" href="{{route($printRoute,$data->id)}}" class="button" tooltip="Print">
            <i class="nc-icon-mini tech_print"></i>
        </a>

        <a href="{{route('procurements.ntp.logs', $data->id)}}" class="button" tooltip="Logs">
            <i class="nc-icon-mini files_archive-content"></i>
        </a>

        <a href="{{route($editRoute,$data->id)}}" class="button" tooltip="Edit">
            <i class="nc-icon-mini design_pen-01"></i>
        </a>


    </div>
</div>

<div class="data-panel">
    <div class="data-panel__section">
        <h3>Details</h3>
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">RFQ Number :</strong> {{$data->rfq_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">UPR Number :</strong> {{$data->upr_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">PO Number :</strong> {{$po_model->po_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Prepared Date :</strong> {{$data->prepared_date}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Prepared By :</strong> {{($data->users) ? $data->users->first_name ." ". $data->users->surname : ""}} </li>

            @if($data->received_by)
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Received Date :</strong> {{$data->award_accepted_date}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Received by :</strong> {{$data->received_by}} </li>
            @endif

        </ul>
    </div>

    <div class="six columns pull-right">
        <h3>Proponent Details</h3>
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Name :</strong> {{$supplier->name}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Owner :</strong> {{$supplier->owner}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Address :</strong> {{$supplier->address}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">TIN :</strong> {{$supplier->tin}} </li>
        </ul>
    </div>

    <div class="six columns pull-right">
        <h3></h3>
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Cellphone # :</strong> {{$supplier->cell_1}} &nbsp;</li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Phone # :</strong> {{$supplier->phone_1}} &nbsp;</li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">FAX :</strong> {{$supplier->fax_1}} &nbsp;</li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Email :</strong> {{$supplier->email_1}} &nbsp;</li>
        </ul>
    </div>
</div>
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
                @foreach($po_model->items as $item)
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

$('#signatory-button').click(function(e){
    e.preventDefault();
    $('#signatory-modal').addClass('is-visible');
})


var award_accepted_date = new Pikaday(
{
    field: document.getElementById('id-field-award_accepted_date'),
    firstDay: 1,
    defaultDate: new Date(),
    setDefaultDate: new Date(),
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});


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

var expected_date = new Pikaday(
{
    field: document.getElementById('id-field-expected_date'),
    firstDay: 1,
    defaultDate: new Date(),
    setDefaultDate: new Date(),
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});


</script>
@stop