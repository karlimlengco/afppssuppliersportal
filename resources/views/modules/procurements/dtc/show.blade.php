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
        <button type="button" class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                @if(!$data->date_delivered_to_coa)
                    <a class="button__options__item topbar__utility__button--modal" href="#">Proceed</a>
                @endif
            </div>
        </button>
    </div>
</div>

<div class="row">
    <div class="six columns pull-left">
        <h3>Delivery Details</h3>
        <ul>
            <li> <strong>UPR No. :</strong> {{$data->upr_number}} </li>
            <li> <strong>RFQ No. :</strong> {{$data->rfq_number}} </li>
            <li> <strong>Delivery No. :</strong> {{$data->delivery_number}} </li>
            <li> <strong>Delivery Date :</strong> {{$data->delivery_date}} </li>
            <li> <strong>Notes :</strong> {{$data->notes}} </li>

            @if($data->inspection_status)
            <li> <strong>Inspection Status :</strong> {{$data->inspection_status}} </li>
            @endif
            @if($data->date_delivered_to_coa)
            <li> <strong>Date Delivery To COA. :</strong> {{$data->date_delivered_to_coa}} </li>
            <li> <strong>Delivered By. :</strong> {{($data->deliveryMan) ?  $data->deliveryMan->first_name .''. $data->deliveryMan->surname :""}} </li>
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