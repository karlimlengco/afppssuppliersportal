@section('title')
Purchase Order
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
    @include('modules.partials.modals.mfo')
    @include('modules.partials.modals.pcco')
    {{-- @include('modules.partials.modals.ntp') --}}
    @include('modules.partials.modals.po_signatory')
    @include('modules.partials.modals.coa-approval')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns utility utility--align-right" >
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>




        <span type="button" class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">



                @if($data->status == 'COA Approved')

               {{--      @if(count($data->ntp) != 0)
                        <a href="{{route('procurements.ntp.show', $data->ntp->id)}}" class="button__options__item"> View Notice To Proceed</a>
                    @else
                        <a href="#" class="button__options__item" id="ntp-button"> Notice To Proceed</a>
                    @endif --}}
                @endif

                <a target="_blank" href="{{route('procurements.purchase-orders.print-contract', $data->id)}}" class="button__options__item" > Print Contract</a>
                <a target="_blank" href="{{route('procurements.purchase-orders.print-terms', $data->id)}}" class="button__options__item" > Print Terms</a>
                <a target="_blank" href="{{route('procurements.purchase-orders.print-coa', $data->id)}}" class="button__options__item" > Print COA Approval</a>
                <a target="_blank" href="{{route('procurements.purchase-orders.print2', $data->id)}}" class="button__options__item" tooltip="Print">
                    PRINT Form 2
                </a>

                <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" class="button__options__item" tooltip="UPR"> Unit Purchase Request</a>
                <a href="{{route('procurements.purchase-orders.logs', $data->id)}}" class="button__options__item">View Logs</a>
                <a class="button__options__item" href="{{route('procurements.unit-purchase-requests.timelines', $data->upr_id)}}">View Timelines</a>


            </div>
        </span>

        {{-- <a href="#" id="signatory-button" class="button" tooltip="Signatories"><i class="nc-icon-mini business_sign"></i> </a> --}}
        @if($data->type != 'contract')
        <a target="_blank" href="{{route('procurements.purchase-orders.print', $data->id)}}" class="button" tooltip="Print">
            <i class="nc-icon-mini tech_print"></i>
        </a>
        @endif
        <a href="{{route($editRoute,$data->id)}}" class="button" tooltip="Edit">
            <i class="nc-icon-mini design_pen-01"></i>
        </a>


    </div>

    <hr>
    <br>
    <div class="twelve columns align-right utility utility--align-right">
        <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="UPR" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
        <span class="button--pull-left" style="padding-top:10px">Go to UPR</span>

        @if(!$data->funding_released_date)
            Funding
            <a href="#" id="pcco-button" tooltip="Funding" class="button button--pull-right"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @elseif(!$data->mfo_released_date && $data->funding_released_date)
            MFO Funding/Obligation
            <a href="#" id="mfo-button" tooltip="MFO Funding/Obligation" class="button button--pull-right"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @elseif($data->mfo_released_date && $data->funding_released_date && !$data->coa_approved_date)
            PO Approval
            <a href="#" id="coa-button" tooltip="PO Approval" class="button button--pull-right"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @else
            Go to UPR
            <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="Accept NOA" class="button button--pull-right"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @endif

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
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Type :</strong> <span style="text-transform: uppercase">{{$data->type}}</span> </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Purchase Number :</strong> {{$data->po_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Purchase Date :</strong> @if($data->purchase_date) {{CreateCarbon('Y-m-d', $data->purchase_date)->format('d F Y')}}@endif  </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Bid Amount :</strong> {{ formatPrice($data->bid_amount)}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Payment Term :</strong> {{($data->terms) ? $data->terms->name : ""}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Prepared By :</strong> {{($data->users) ? $data->users->first_name ." ". $data->users->surname : ""}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Status :</strong> {{ucfirst($data->status)}} </li>
            @if($data->coa_approved_date)
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">COA Approved Date :</strong>@if($data->coa_approved_date) {{CreateCarbon('Y-m-d', $data->coa_approved_date)->format('d F Y')}}@endif  </li>
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">COA File :</strong> <a target="_blank" href="{{route('procurements.purchase-orders.coa-file',$data->id)}}">{{$data->coa_file}} </a></li>
            @endif
        </ul>
    </div>

    <div class="data-panel__section">
        <h3>Approval Details</h3>
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Funding Released Date :</strong>  @if($data->funding_released_date) {{CreateCarbon('Y-m-d', $data->funding_released_date)->format('d F Y')}}@endif  </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Funding Received Date :</strong>  @if($data->funding_received_date) {{CreateCarbon('Y-m-d', $data->funding_received_date)->format('d F Y')}}@endif</li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Funding Remarks :</strong> {{$data->funding_remarks}} &nbsp;</li>

            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">MFO Released Date :</strong> @if($data->mfo_released_date) {{CreateCarbon('Y-m-d', $data->mfo_released_date)->format('d F Y')}}@endif</li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">MFO Funding/Obligation :</strong> @if($data->mfo_received_date) {{CreateCarbon('Y-m-d', $data->mfo_received_date)->format('d F Y')}}@endif </li>
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
                    <th>Type</th>
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
                        <td style="text-transform: uppercase;">{{$item->type}}</td>
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