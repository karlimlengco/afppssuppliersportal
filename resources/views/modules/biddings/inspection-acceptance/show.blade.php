@section('title')
Inspection And Acceptance Report
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
    @include('modules.partials.modals.iar-signatories')
    @include('modules.partials.modals.iar-accept')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns utility utility--align-right" >
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>


        <button class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                <a id="signatory-button" href="#" class="button__options__item" > Signatories</a>
                <a id="signatory-button" href="{{route('procurements.unit-purchase-requests.show', $data->upr->id)}}" class="button__options__item" > Unit Purchase Request</a>

                <a href="{{route('procurements.inspection-and-acceptance.logs', $data->id)}}" class="button__options__item" tooltip="Logs">
                    View Logs
                </a>

            </div>
        </button>


        <a target="_blank" class="button" href="{{route($printRoute, $data->id)}}"><i class="nc-icon-mini tech_print"></i></a>


        <a href="{{route($editRoute,$data->id)}}" class="button" tooltip="Edit">
            <i class="nc-icon-mini design_pen-01"></i>
        </a>
    </div>


    <hr>
    <br>
    <div class="twelve columns align-right utility utility--align-right">
        <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="UPR" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
        <span class="button--pull-left" style="padding-top:10px">Go to UPR</span>
        @if(!$data->accepted_date)
            Accept Inspection
            <a class="button" tooltip="Accept" id="accept-button"  href="#"><i class="nc-icon-mini ui-1_check-bold"></i></a>
        @else
            Go to UPR
            <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="Accept NOA" class="button button--pull-right"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @endif
    </div>
</div>

<div class="data-panel">
    <div class="data-panel__section">
        <h3>Inspection Details</h3>
        <ul  class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">UPR No. :</strong> {{$data->upr_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Delivery No. :</strong> {{$data->delivery_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Status :</strong> {{$data->status}} </li>
        </ul>
    </div>

    <div class="data-panel__section">
        <h3></h3>
        <ul  class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Inspection Date :</strong> @if($data->inspection_date) {{CreateCarbon('Y-m-d', $data->inspection_date)->format('d F Y')}}@endif</li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Nature Of Delivery :</strong> {{$data->nature_of_delivery}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Prepared By :</strong> {{($data->users) ? $data->users->first_name .' '.$data->users->surname : ""}} </li>
            @if($data->accepted_date)
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Date Accepted :</strong> @if($data->accepted_date) {{CreateCarbon('Y-m-d', $data->accepted_date)->format('d F Y')}}@endif</li>
            @endif
        </ul>
    </div>

    <div class="data-panel__section">
        <h3>Supplier Details</h3>
        <ul  class="data-panel__list">
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

<div class="data-panel">
    <div class="data-panel__section">
        <h3>Invoice Details</h3>
        <ul  class="data-panel__list">

            @foreach($data->invoices as $invoice)
                <li class="data-panel__list__item">
                    <strong> {{$invoice->invoice_number}}:</strong>
                        {{$invoice->invoice_date}}
                </li>
            @endforeach
        </ul>
    </div>

</div>


<div class="data-panel">
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Findings :</strong> {{$data->findings}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Recommendation :</strong> {{$data->recommendation}}   </li>
        </ul>
    </div>
</div>

@stop

@section('scripts')
<script type="text/javascript">

$('#signatory-button').click(function(e){
    e.preventDefault();
    $('#signatory-modal').addClass('is-visible');
})
$('#accept-button').click(function(e){
    e.preventDefault();
    $('#accept-modal').addClass('is-visible');
})


var accepted_date = new Pikaday(
{
    field: document.getElementById('id-field-accepted_date'),
    firstDay: 1,
    defaultDate: new Date(),
    setDefaultDate: new Date(),
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});
</script>
@stop