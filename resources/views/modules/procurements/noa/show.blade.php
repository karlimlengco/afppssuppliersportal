@section('title')
Notice Of Award
@stop

@section('modal')
    @include('modules.partials.modals.signatory')
    @include('modules.partials.modals.received')
@stop

@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h1> </h1>
    </div>
    <div class="six columns align-right">

        <a class="button" href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="UPR"> <span class="nc-icon-glyph business_agenda"></span> </a>
        <a href="{{route('procurements.blank-rfq.show', $data->rfq_id)}}" class="button" tooltip="RFQ"> <span class=" nc-icon-glyph ui-1_edit-74"></span> </a>
        <a href="{{route('procurements.canvassing.show', $data->id)}}" class="button" tooltip="CANVASSING"> <span class=" nc-icon-glyph business_award-49"></span>  </a>

        <a href="#" id="signatory-button" class="button" tooltip="SIGNATORIES"> <span class=" nc-icon-glyph business_signature"></span>  </a>

        @if(!$awardee->received_by && !$awardee->award_accepted_date)
            <a class="button" id="received-button" href="#">Received</a>
        @endif
        <a class="button" href="{{route($printRoute,$data->id)}}">PRINT</a>
        <a class="button" href="{{route($indexRoute)}}">Back</a>
    </div>
</div>
<br>
<br>
<br>
<div class="row">
    <div class="six columns pull-left">
        <h3>Canvass Details</h3>
        <ul>
            <li> <strong>UPR No. :</strong> {{$data->upr_number}} </li>
            <li> <strong>RFQ No. :</strong> {{$data->rfq_number}} </li>
            <li> <strong>Canvass Date :</strong> {{$data->canvass_date}} </li>
            <li> <strong>Adjourned Time :</strong> {{$data->adjourned_time}} </li>
            @if($awardee->awarded_date  )
            <li> <strong>Award Date :</strong> {{$awardee->awarded_date}} </li>
            @endif
            @if($awardee->received_by && $awardee->award_accepted_date)
                <li> <strong>Received By :</strong> {{$awardee->received_by}} </li>
                <li> <strong>Received Date :</strong> {{$awardee->award_accepted_date}} </li>
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