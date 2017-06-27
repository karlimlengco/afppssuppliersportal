@section('title')
Delivery To COA
@stop

@section('modal')
    @include('modules.partials.modals.dtc-proceed')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns utility utility--align-right" >
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button type="button" style="margin-right:40px" class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                @if(!$data->date_delivered_to_coa)
                    <a class="button__options__item topbar__utility__button--modal" href="#">Proceed</a>
                @endif

                <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" class="button__options__item" tooltip="UPR"> Unit Purchase Request</a>

                <a href="{{route('procurements.blank-rfq.show', $data->rfq_id)}}" class="button__options__item" tooltip="RFQ"> Request For Quotation</a>

                <a href="{{route('procurements.purchase-orders.show', $data->po_id)}}" class="button__options__item" tooltip="NTP"> Purchase Order</a>
            </div>
        </button>
    </div>
</div>

<div class="data-panel">
    <div class="data-panel__section">
        <h3>Delivery Details</h3>
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">UPR No. :</strong> {{$data->upr_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">RFQ No. :</strong> {{$data->rfq_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Delivery No. :</strong> {{$data->delivery_number}} </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <h3></h3>
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Delivery Date :</strong> {{$data->delivery_date}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Notes :</strong> {{$data->notes}} </li>

            @if($data->inspection_status)
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Inspection Status :</strong> {{$data->inspection_status}} </li>
            @endif
            @if($data->date_delivered_to_coa)
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Date Delivery To COA. :</strong> {{$data->date_delivered_to_coa}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Delivered By. :</strong> {{($data->deliveryMan) ?  $data->deliveryMan->first_name .''. $data->deliveryMan->surname :""}} </li>
            @endif
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
@stop

@section('scripts')

<script type="text/javascript">

    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-date_delivered_to_coa'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    // end datepicker
</script>
@stop