@section('title')
Notice Of Delivery
@stop

@section('modal')
    @include('modules.partials.modals.signatory')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

        <button type="button" class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" class="button__options__item" tooltip="UPR"> Unit Purchase Request</a>

                <a href="{{route('procurements.blank-rfq.show', $data->rfq_id)}}" class="button__options__item" tooltip="RFQ"> Request For Quotation</a>

                <a href="{{route('procurements.purchase-orders.show', $data->po_id)}}" class="button__options__item" tooltip="NTP"> Purchase Order</a>

                @if($data->delivery_date)
                    @if(!$data->date_completed)
                        <a class="button__options__item" href="{{route($completeRoute,$data->id)}}">Complete</a>
                    @else
                        @if(count($data->inspections) == 0)
                        <a class="button__options__item" href="{{route('procurements.inspection-and-acceptance.create-from-delivery',$data->id)}}">Start Inspection</a>
                        @else
                            <a class="button__options__item" href="{{route('procurements.inspection-and-acceptance.show',$data->inspections->id)}}">View Inspection</a>
                        @endif
                    @endif
                @endif
                <a target="_blank" class="button__options__item" id="signatory-button" href="#">Signatory</a>
            </div>
        </button>

        <a href="{{route('procurements.delivery-orders.print', $data->id)}}" class="button" tooltip="PRINT"><i class="nc-icon-mini tech_print "></i></a>
        @if(!$data->delivery_date)
            <a href="{{route($editRoute, $data->id)}}" class="button" tooltip="Edit"><i class="nc-icon-mini design_pen-01"></i></a>
        @else

        @endif
    </div>
</div>

<div class="data-panel">
    <div class="data-panel__section">
        <h1>Delivery Details</h1>
        <ul class="data-panel__list">
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">RFQ Number :</strong> {{$data->rfq_number}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">UPR Number :</strong> {{$data->upr_number}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Expected Delivery Date :</strong> {{$data->expected_date}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Created By :</strong> {{($data->creator) ? $data->creator->first_name .' '. $data->creator->surname : " "}} </li>

        </ul>
    </div>

    <div class="data-panel__section">
        <h1></h1>
        <ul  class="data-panel__list">
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Status :</strong> {{ucwords($data->status)}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Delivery Date :</strong> {{$data->delivery_date}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Delivery Number :</strong> {{$data->delivery_number}} </li>
        </ul>
    </div>

    <div class="data-panel__section">
        <h1></h1>
        <ul  class="data-panel__list">

            @if($data->delivery_date)
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Delivery Note :</strong> {{$data->notes}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Received by :</strong> {{($data->receiver) ? $data->receiver->first_name ." ". $data->receiver->surname : ""}} </li>
                @if($data->status)
                    <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Completed Date :</strong> {{$data->date_completed}} </li>
                @endif
            @endif
        </ul>
    </div>
</div>
@stop

@section('scripts')
<script type="text/javascript">

var delivery_date = new Pikaday(
{
    field: document.getElementById('id-field-delivery_date'),
    firstDay: 1,
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});

$('#signatory-button').click(function(e){
    e.preventDefault();
    $('#signatory-modal').addClass('is-visible');
})


</script>
@stop