@section('title')
Notice Of Award
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

                <a href="{{route('procurements.blank-rfq.show', $data->rfq_id)}}" class="button__options__item">Request For Quotation</a>

                <a href="{{route('procurements.canvassing.show', $data->canvass_id)}}" class="button__options__item">Canvassing</a>

                <a href="#"  id="signatory-button" class="button__options__item">Signatories</a>

                @if($data->status == 'approved')
                    <a href="#"  id="received-button" class="button__options__item">Received</a>
                @endif
                @if($data->status == 'pending')
                    <a href="#"  id="accept-button" class="button__options__item">Accept NOA</a>
                @endif
            </div>
        </button>

{{--         @if(!$data->received_by && !$data->award_accepted_date)
            <a class="button" id="received-button" href="#">Received</a>
        @endif --}}
        <a target="_blank" class="button" href="{{route($printRoute,$data->id)}}"><i class="nc-icon-mini tech_print"></i></a>

        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
    </div>
</div>
<br>
<br>
<br>
<div class="row">
    <div class="six columns pull-left">
        <h3>Details</h3>
        <ul>
            <li> <strong>UPR No. :</strong> {{$canvass->upr_number}} </li>
            <li> <strong>RFQ No. :</strong> {{$canvass->rfq_number}} </li>
            <li> <strong>Status :</strong> {{$data->status}} </li>
            <li> <strong>Canvass Date :</strong> {{$canvass->canvass_date}} </li>
            <li> <strong>Adjourned Time :</strong> {{$canvass->adjourned_time}} </li>
            @if($data->awarded_date  )
            <li> <strong>Award Date :</strong> {{$data->awarded_date}} </li>
            @endif
            @if($data->accepted_date  )
            <li> <strong>Approved Date :</strong> {{$data->accepted_date}} </li>
            <li> <strong>Approved Copy :</strong> <a target="_blank" href="{{route('procurements.noa.download',$data->id)}}">{{$data->file}}</a> </li>
            @endif
            @if($data->received_by && $data->award_accepted_date)
                <li> <strong>Received By :</strong> {{$data->received_by}} </li>
                <li> <strong>Received Date :</strong> {{$data->award_accepted_date}} </li>
            @endif
        </ul>
    </div>
    <div class="six columns pull-right">
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
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    // end datepicker
</script>
@stop