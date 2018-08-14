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
    @include('modules.partials.modals.ntp_philgeps')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns utility utility--align-right">
        <span class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">

                <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" class="button__options__item" tooltip="UPR"> Unit Purchase Request</a>


                <a href="{{route('procurements.ntp.logs', $data->id)}}" class="button__options__item" tooltip="Logs">
                    View Logs
                </a>
                <a class="button__options__item" href="{{route('procurements.unit-purchase-requests.timelines', $data->upr_id)}}">View Timelines</a>

                @if($data->received_by && $data->upr->total_amount > 50000 && !$data->philgeps_posting)
                    <a href="#" class="button__options__item" id="ntp-philgeps-button" tooltip="PhilGeps Posting">PhilGeps Posting</a>
                @endif


            </div>
        </span>
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        {{-- <a href="#" id="signatory-button" class="button" tooltip="Signatories"><i class="nc-icon-mini business_sign"></i> </a> --}}


        <a target="_blank" href="{{route($printRoute,$data->id)}}" class="button" tooltip="Print">
            <i class="nc-icon-mini tech_print"></i>
        </a>

        <a href="{{route($editRoute,$data->id)}}" class="button" tooltip="Edit">
            <i class="nc-icon-mini design_pen-01"></i>
        </a>


    </div>

    <hr>
    <br>
    <div class="twelve columns align-right utility utility--align-right">
        <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="UPR" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
        <span class="button--pull-left" style="padding-top:10px">Go to UPR</span>
        @if(!$data->award_accepted_date)
            Received
            <a href="#" id="proceed-ntp-button" tooltip="Received" class="button button--pull-right"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @else
            Go to UPR
            <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="Accept NOA" class="button button--pull-right"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @endif

    </div>
</div>

<div class="data-panel">
    <div class="data-panel__section">
        <h3>Details</h3>
        <ul class="data-panel__list">
          {{dd($po_model)}}
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">UPR Number :</strong> {{$data->upr_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">PO Number :</strong> {{$po_model->po_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Prepared Date :</strong>   @if($data->prepared_date) {{CreateCarbon('Y-m-d H:i:s', $data->prepared_date)->format('d F Y')}}@endif</li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Prepared By :</strong> {{($data->users) ? $data->users->first_name ." ". $data->users->surname : ""}} </li>

            @if($data->received_by)
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Received Date :</strong>  @if($data->award_accepted_date) {{CreateCarbon('Y-m-d', $data->award_accepted_date)->format('d F Y')}}@endif </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Conforme :</strong> {{$data->received_by}} </li>
            @endif

            @if($data->philgeps_posting)
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Philgeps Posting :</strong> {{$data->philgeps_posting}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Remarks from Philgeps Posting :</strong> {{$data->philgeps_remarks or "&nbsp;"}}  </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Action from Philgeps Posting :</strong> {{$data->philgeps_remarks or "&nbsp;"}}  </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Action from Philgeps Posting :</strong> {{$data->philgeps_days }}  </li>
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

$('#ntp-philgeps-button').click(function(e){
    e.preventDefault();
    $('#ntp-philgeps-modal').addClass('is-visible');
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