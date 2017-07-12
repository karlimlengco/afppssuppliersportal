@section('title')
Request For Bid
@stop

@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
@stop

@section('modal')
    @include('modules.partials.bid-modals.rfb-received')
    @include('modules.partials.bid-modals.received-noa')
@stop

@section('contents')
<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <button class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                @if(!$data->received_date)
                    <a href="#" class="button__options__item" id="rfb-received-button">Received</a>
                @else
                    @if(count($data->noa) == 0)
                        <a href="#" class="button__options__item" id="notice-acceptance-button">Notice Of Acceptance</a>
                    @else
                        <a href="{{route('biddings.noa-acceptance.index')}}" class="button__options__item" >Notice Of Acceptance</a>
                    @endif
                @endif
            </div>
        </button>

        <a href="{{route($editRoute,$data->id)}}" class="button" tooltip="Edit">
            <i class="nc-icon-mini design_pen-01"></i>
        </a>

        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

    </div>
</div>

<div class="data-panel">
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Ref No. :</strong> {{$data->rfb_number}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">UPR No. :</strong> {{$data->upr_number}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Status :</strong> {{ ucfirst($data->status) }} </li>
            @if($data->received_date)
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Received At :</strong> {{ $data->received_date }} </li>
            @endif
        </ul>
    </div>
    <div class="data-panel__section">
        <ul>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">BACSEC :</strong> {{ $data->bacsec->name  }} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">ABC :</strong> {{ formatPrice($data->upr->total_amount) }} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Released Date :</strong> {{ $data->released_date  }} </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">TransactionDate :</strong> {{ $data->transaction_date }} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Remarks :</strong> {{ $data->remarks or "&nbsp;"}}  </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Processed By :</strong> {{($data->processor) ? $data->processor->first_name ." ". $data->processor->surname :""}} </li>
        </ul>
    </div>
</div>


@stop

@section('scripts')
<script src="/vendors/timepicker/timepicker.min.js"></script>
<script type="text/javascript">

    $('#rfb-received-button').click(function(e){
        e.preventDefault();
        $('#rfb-received-modal').addClass('is-visible');
    })
    $('#notice-acceptance-button').click(function(e){
        e.preventDefault();
        $('#notice-acceptance-modal').addClass('is-visible');
    })
    // datepicker
    // pickmeup('#id-field-date_processed', {
    //     format  : 'Y-m-d'
    // });
    var received_date = new Pikaday(
    {
        field: document.getElementById('id-field-received_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });


    var received_noa = new Pikaday(
    {
        field: document.getElementById('id-field-received_noa'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
</script>
@stop