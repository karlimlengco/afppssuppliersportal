@section('title')
Purchase Order
@stop

@section('modal')
    @include('modules.partials.modals.mfo')
    @include('modules.partials.modals.pcco')
    @include('modules.partials.modals.ntp')
    @include('modules.partials.modals.po_signatory')
    @include('modules.partials.modals.coa-approval')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns utility utility--align-right" >
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>




        <button type="button" class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">

                @if(!$data->funding_released_date)
                    <a class=" button__options__item" id="pcco-button" href="#">Funding</a>
                @endif

                @if(!$data->mfo_released_date && $data->funding_released_date)
                    <a class=" button__options__item" id="mfo-button" href="#">MFO Funding/Obligation</a>
                @endif

                @if($data->mfo_released_date && $data->funding_released_date && !$data->coa_approved_date)
                    <a href="#" class="button__options__item" id="coa-button">  Approval</a>
                @endif

                @if($data->status == 'COA Approved')

                    @if(count($data->ntp) != 0)
                        <a href="{{route('procurements.ntp.show', $data->ntp->id)}}" class="button__options__item"> View Notice To Proceed</a>
                    @else
                        <a href="#" class="button__options__item" id="ntp-button"> Notice To Proceed</a>
                    @endif
                @endif

                <a href="#" class="button__options__item" id="signatory-button"> Signatories</a>
                <a target="_blank" href="{{route('procurements.purchase-orders.print-terms', $data->id)}}" class="button__options__item" id="signatory-button"> Print Terms</a>
                <a target="_blank" href="{{route('procurements.purchase-orders.print-coa', $data->id)}}" class="button__options__item" id="signatory-button"> Print COA Approval</a>

                <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" class="button__options__item" tooltip="UPR"> Unit Purchase Request</a>
                <a href="{{route('procurements.blank-rfq.show', $data->rfq_id)}}" class="button__options__item" tooltip="RFQ"> Request For Quotation</a>



                @if(count($data->delivery) != 0)
                    <a href="{{route('procurements.delivery-orders.show', $data->delivery->id)}}" class="button__options__item" tooltip="Delivery"> Delivery</a>
                @endif
            </div>
        </button>

        <a target="_blank" href="{{route('procurements.purchase-orders.print', $data->id)}}" class="button" tooltip="Print">
            <i class="nc-icon-mini tech_print"></i>
        </a>
        <a href="{{route('procurements.purchase-orders.logs', $data->id)}}" class="button" tooltip="Logs">
            <i class="nc-icon-mini files_archive-content"></i>
        </a>
        <a href="{{route($editRoute,$data->id)}}" class="button" tooltip="Edit">
            <i class="nc-icon-mini design_pen-01"></i>
        </a>


    </div>
</div>

        {{-- <a class="button" href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="UPR"> <span class="nc-icon-glyph business_agenda"></span> </a>
        <a href="{{route('procurements.blank-rfq.show', $data->rfq_id)}}" class="button" tooltip="RFQ"> <span class=" nc-icon-glyph ui-1_edit-74"> --}}
        {{-- <a href="{{route('procurements.ntp.show', $data->id)}}" class="button" tooltip="NTP"> NTP</a> --}}

   {{--      @if(count($data->delivery) != 0)
        <a href="{{route('procurements.delivery-orders.show', $data->delivery   ->id)}}" class="button" tooltip="DELIVERY"> <span class=" nc-icon-glyph transportation_truck-front"></span>  </a>
        @endif
 --}}
{{--
        @if($data->pcco_released_date and $data->mfo_released_date)
            <a class="button" href="#">Print</a>
        @endif --}}
        {{-- <a class="button" href="{{route($indexRoute,$data->rfq_id)}}">Back</a> --}}


<div class="data-panel">
    <div class="data-panel__section">

        <h3>Purchase Details</h3>
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Purchase Number :</strong> {{$data->po_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Purchase Date :</strong> {{$data->purchase_date}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Bid Amount :</strong> {{ formatPrice($data->bid_amount)}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Payment Term :</strong> {{($data->terms) ? $data->terms->name : ""}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Prepared By :</strong> {{($data->users) ? $data->users->first_name ." ". $data->users->surname : ""}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Status :</strong> {{ucfirst($data->status)}} </li>
            @if($data->coa_approved_date)
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">COA Approved Date :</strong> {{$data->coa_approved_date}} </li>
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">COA File :</strong> <a target="_blank" href="{{route('procurements.purchase-orders.coa-file',$data->id)}}">{{$data->coa_file}} </a></li>
            @endif
        </ul>
    </div>

    <div class="data-panel__section">
        <h3>Approval Details</h3>
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Funding Released Date :</strong> {{$data->funding_released_date}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Funding Received Date :</strong> {{$data->funding_received_date}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Funding Remarks :</strong> {{$data->funding_remarks}} </li>

            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">MFO Released Date :</strong> {{$data->mfo_released_date}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">MFO Funding/Obligation :</strong> {{$data->mfo_received_date}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">MFO Remarks :</strong> {{$data->mfo_remarks}} </li>
        </ul>
    </div>

    <div class="data-panel__section">
        <h3>Proponent Details</h3>
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Name :</strong> {{$supplier->name}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Owner :</strong> {{$supplier->owner}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Address :</strong> {{$supplier->address}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">TIN :</strong> {{$supplier->tin}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Cellphone # :</strong> {{$supplier->cell_1}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Phone # :</strong> {{$supplier->phone_1}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">FAX :</strong> {{$supplier->fax_1}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Email :</strong> {{$supplier->email_1}} </li>
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
$('#ntp-button').click(function(e){
    e.preventDefault();
    $('#ntp-modal').addClass('is-visible');
})
$('#coa-button').click(function(e){
    e.preventDefault();
    $('#coa-modal').addClass('is-visible');
})
$('#mfo-button').click(function(e){
    e.preventDefault();
    $('#mfo-modal').addClass('is-visible');
})
$('#signatory-button').click(function(e){
    e.preventDefault();
    $('#signatory-modal').addClass('is-visible');
})

$('#proceed-ntp-button').click(function(e){
    e.preventDefault();
    $('#proceed-ntp-modal').addClass('is-visible');
})


var mfo_released_date = new Pikaday(
{
    field: document.getElementById('id-field-mfo_released_date'),
    firstDay: 1,
    defaultDate: new Date(),
    setDefaultDate: new Date(),
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});

var preparared_date = new Pikaday(
{
    field: document.getElementById('id-field-preparared_date'),
    firstDay: 1,
    defaultDate: new Date(),
    setDefaultDate: new Date(),
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});


var mfo_received_date = new Pikaday(
{
    field: document.getElementById('id-field-mfo_received_date'),
    firstDay: 1,
    defaultDate: new Date(),
    setDefaultDate: new Date(),
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});


var pcco_released_date = new Pikaday(
{
    field: document.getElementById('id-field-funding_released_date'),
    firstDay: 1,
    defaultDate: new Date(),
    setDefaultDate: new Date(),
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});


var coa_approved_date = new Pikaday(
{
    field: document.getElementById('id-field-coa_approved_date'),
    firstDay: 1,
    defaultDate: new Date(),
    setDefaultDate: new Date(),
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});

var pcco_received_date = new Pikaday(
{
    field: document.getElementById('id-field-funding_received_date'),
    firstDay: 1,
    defaultDate: new Date(),
    setDefaultDate: new Date(),
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});

</script>
@stop