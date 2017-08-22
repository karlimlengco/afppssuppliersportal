@section('title')
Notice Of Award
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
    @include('modules.partials.modals.accept-noa')
    @include('modules.partials.modals.received')
@stop

@section('contents')

<div class="row">

    <div class="twelve columns align-right utility utility--align-right">
        <button type="button" class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" class="button__options__item">Unit Purchase Request</a>

                <a href="#"  id="signatory-button" class="button__options__item">Signatories</a>


                 <a href="{{route('procurements.noa.logs', $data->id)}}" class="button__options__item" tooltip="Logs">
                     View Logs
                 </a>
            </div>
        </button>

{{--         @if(!$data->received_by && !$data->award_accepted_date)
            <a class="button" id="received-button" href="#">Received</a>
        @endif --}}
        <a target="_blank" class="button" href="{{route($printRoute,$data->id)}}"><i class="nc-icon-mini tech_print"></i></a>


        <a href="{{route($editRoute,$data->id)}}" class="button" tooltip="Edit">
            <i class="nc-icon-mini design_pen-01"></i>
        </a>
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
    </div>

    <hr>
    <br>
    <div class="twelve columns align-right utility utility--align-right">

        <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="UPR" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
        <span class="button--pull-left" style="padding-top:10px">Go to UPR</span>

        @if($data->status == 'approved')
            Received NOA
            <a href="#" id="received-button" tooltip="Received NOA" class="button button--pull-right"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @elseif($data->status == 'pending')
            Accept NOA
            <a href="#" id="accept-button" tooltip="Accept NOA" class="button button--pull-right"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @else
            Go to UPR
            <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="Accept NOA" class="button button--pull-right"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @endif
    </div>
</div>
<div class="data-panel">
    <div class="data-panel__section">
        <h3>Details</h3>
        @if(isset($canvass))
        <h3>Details</h3>
        <ul  class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">UPR No. :</strong> {{$canvass->upr_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">RFQ No. :</strong> {{$canvass->rfq_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Status :</strong> {{ ucwords($data->status)}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Canvass Date :</strong> {{$canvass->canvass_date}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Adjourned Time :</strong> {{$canvass->adjourned_time}} </li>
            @if($data->awarded_date  )
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Award Date :</strong> {{$data->awarded_date}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Award By :</strong> {{($data->awarder) ? $data->awarder->name : ""}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Seconded By :</strong> {{($data->seconder) ? $data->seconder->name : ""}} </li>
            @endif
        </ul>

        @else

        <ul  class="data-panel__list">
            @if($data->awarded_date  )
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Award Date :</strong> {{$data->awarded_date}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Award By :</strong> {{($data->awarder) ? $data->awarder->name : ""}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Seconded By :</strong> {{($data->seconder) ? $data->seconder->name : ""}} </li>
            @endif
        </ul>
        @endif
    </div>
    <div class="data-panel__section">
        <h3></h3>
        <ul  class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">ABC :</strong> {{formatPrice($data->upr->total_amount)}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">BID Amount :</strong> @if($data->biddingWinner) {{formatPrice($data->biddingWinner->bid_amount)}} @endif </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Approved Date :</strong> {{$data->accepted_date}} &nbsp;</li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Approved Copy :</strong> <a target="_blank" href="{{route('procurements.noa.download',$data->id)}}">{{$data->file}}</a> &nbsp;</li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Conforme :</strong> {{$data->received_by}}&nbsp; </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Received Date :</strong> {{$data->award_accepted_date}} &nbsp;</li>
        </ul>
    </div>
    <div class="data-panel__section">
        <h3>Proponent Details</h3>
        <ul  class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Name :</strong> {{$supplier->name}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Owner :</strong> {{$supplier->owner}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Address :</strong> {{$supplier->address}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">TIN :</strong>&nbsp; {{$supplier->tin}} &nbsp;</li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Cellphone # :</strong> {{$supplier->cell_1}}&nbsp; </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Phone # :</strong> {{$supplier->phone_1}}&nbsp; </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">FAX :</strong> {{$supplier->fax_1}} &nbsp;</li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Email :</strong> {{$supplier->email_1}}&nbsp; </li>
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

    $('#received-button').click(function(e){
        e.preventDefault();
        $('#received-modal').addClass('is-visible');
    })


    $('#accept-button').click(function(e){
        e.preventDefault();
        $('#accept-modal').addClass('is-visible');
    })

    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-award_accepted_date'),
        firstDay: 1,
        defaultDate: new Date(),
        setDefaultDate: new Date(),
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

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
    // end datepicker
</script>
@stop