@section('title')
Notice Of Delivery
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
    @include('modules.partials.modals.coa-delivery')
    @include('modules.partials.modals.view-attachments-nod')
    @include('modules.partials.modals.dropzone')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

        <button type="button" class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                <a class="button__options__item" id="view-attachments-button" href="#">Attachments</a>

                <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" class="button__options__item" tooltip="UPR"> Unit Purchase Request</a>



                <a href="{{route('procurements.delivery-orders.logs', $data->id)}}" class="button__options__item" tooltip="Logs">
                    View Logs
                </a>

            </div>
        </button>


        <a href="#" id="signatory-button" class="button" tooltip="Signatories"><i class="nc-icon-mini business_sign"></i> </a>

        <a   target="_blank" href="{{route('procurements.delivery-orders.print', $data->id)}}" class="button" tooltip="PRINT"><i class="nc-icon-mini tech_print "></i></a>

        @if( $data->status == 'ongoing')
            <a href="{{route($editRoute, $data->id)}}" class="button" tooltip="Receive"><i class="nc-icon-glyph shopping_delivery lg"></i></a>
        @endif

        <a href="#" id="attachment-button" class="button" tooltip="Attachments"><i class="nc-icon-mini ui-1_attach-86"></i> </a>

        <a href="{{route($editDateRoute,$data->id)}}" class="button" tooltip="Edit">
            <i class="nc-icon-mini design_pen-01"></i>
        </a>
    </div>

    <hr>
    <br>
    <div class="twelve columns align-right utility utility--align-right">
        <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="UPR" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
        <span class="button--pull-left" style="padding-top:10px">Go to UPR</span>

        @if($data->status == 'Delivery Received')
            Complete COA Delivery
            <a href="#" id="coa-button" tooltip="Complete COA Delivery" class="button button--pull-right"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @elseif( $data->status == 'ongoing')
            Receiving Items
            <a href="{{route($editRoute, $data->id)}}" class="button" tooltip="Receive"><i class="nc-icon-glyph shopping_delivery lg"></i></a>
        @else
            Go to UPR
            <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="Accept NOA" class="button button--pull-right"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @endif
    </div>

</div>

<div class="data-panel">
    <div class="data-panel__section">
        <h3>Delivery Details</h3>
        <ul class="data-panel__list">
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">UPR Number :</strong> {{$data->upr_number}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Status :</strong> {{ucwords($data->status)}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Created By :</strong> {{($data->creator) ? $data->creator->first_name .' '. $data->creator->surname : " "}} </li>

        </ul>
    </div>

    <div class="data-panel__section">
        <h3>&nbsp;</h3>
        <ul  class="data-panel__list">
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Transaction Date :</strong> @if($data->transaction_date) {{CreateCarbon('Y-m-d', $data->transaction_date)->format('d F Y')}}@endif &nbsp; </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Expected Delivery Date :</strong>  @if($data->expected_date) {{CreateCarbon('Y-m-d', $data->expected_date)->format('d F Y')}}@endif  </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Delivery Date :</strong> @if($data->delivery_date) {{CreateCarbon('Y-m-d', $data->delivery_date)->format('d F Y')}}@endif &nbsp;</li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Delivery Number :</strong> {{$data->delivery_number}}  &nbsp;</li>
        </ul>
    </div>

    <div class="data-panel__section">
        <h3>&nbsp;</h3>
        <ul  class="data-panel__list">

            @if($data->delivery_date)
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Delivery Note :</strong> {{$data->notes}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Conforme :</strong> {{($data->receiver) ? $data->receiver->first_name ." ". $data->receiver->surname : ""}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">COA Completed Date :</strong> @if($data->date_delivered_to_coa) {{CreateCarbon('Y-m-d', $data->date_delivered_to_coa)->format('d F Y')}}@endif </li>
            @endif
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
                        <td>{{$item->quantity}}/{{$item->received_quantity}}</td>
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
    $('#attachment-button').click(function(e){
        e.preventDefault();
        $('#dropzone-modal').addClass('is-visible');
    })

    $('#view-attachments-button').click(function(e){
        e.preventDefault();
        $('#view-attachments-modal').addClass('is-visible');
    })

    var delivery_date = new Pikaday(
    {
        field: document.getElementById('id-field-delivery_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    var date_delivered_to_coa = new Pikaday(
    {
        field: document.getElementById('id-field-date_delivered_to_coa'),
        firstDay: 1,
            defaultDate: new Date(),
            setDefaultDate: new Date(),
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    $('#signatory-button').click(function(e){
        e.preventDefault();
        $('#signatory-modal').addClass('is-visible');
    })

    $('#coa-button').click(function(e){
        e.preventDefault();
        $('#coa-modal').addClass('is-visible');
    })


</script>
@stop