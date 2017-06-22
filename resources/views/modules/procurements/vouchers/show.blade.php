@section('title')
Vouchers
@stop

@section('modal')
    @include('modules.partials.modals.release-voucher')
    @include('modules.partials.modals.received-voucher')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns utility utility--align-right" >
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button  type="button" class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                    <a class="button__options__item" href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}">Unit of Purchase Request</a>
                    <a class="button__options__item" href="{{route('procurements.blank-rfq.show', $data->rfq_id)}}">Request For Quotation</a>

                @if(!$data->payment_release_date)
                    <a href="#" id="release-button" class="button__options__item " tooltip="Release Payment"> Release Payment</a>
                @endif

                @if(!$data->payment_received_date and $data->payment_release_date)
                    <a href="#" id="received-button" class="button__options__item" tooltip="Received Payment"> Received Payment</a>
                @endif
            </div>
        </button>
        <a class="button" href="{{route($editRoute,$data->id)}}"><i class="nc-icon-mini design_pen-01"></i></a>
    </div>
</div>


<div class="row">
    <div class="six columns pull-left">
        <ul>
            <li> <strong>UPR No. :</strong> {{$data->upr_number}} </li>
            <li> <strong>RFQ No. :</strong> {{$data->rfq_number}} </li>
            <li> <strong>Transaction DAte :</strong> {{$data->transaction_date}} </li>
            <li> <strong>BIR Address. :</strong> {{$data->bir_address}} </li>
            <li> <strong>Final Tax :</strong> {{$data->final_tax}} </li>
            <li> <strong>Expanded Witholding Tax :</strong> {{$data->expanded_witholding_tax}} </li>
            <li> <strong>Prepared By:</strong> {{($data->users) ? $data->users->first_name .' '.$data->users->surname : " "}} </li>
        </ul>
    </div>
    <div class="six columns pull-right">
        <ul>

            @if($data->payment_release_date)
                <li> <strong>Payment Release Date :</strong> {{$data->payment_release_date}} </li>
                <li> <strong>Releaser :</strong> {{($data->releaser) ? $data->releaser->first_name .' '.$data->releaser->surname : " "}} </li>
            @endif

            @if($data->payment_received_date)
                <li> <strong>Payment Received Date :</strong> {{$data->payment_received_date}} </li>
                <li> <strong>Receiver :</strong> {{($data->recevier) ? $data->recevier->first_name .' '.$data->recevier->surname : " "}} </li>
            @endif
        </ul>
    </div>
</div>
@stop

@section('scripts')
<script type="text/javascript">

$('#received-button').click(function(e){
    e.preventDefault();
    $('#received-modal').addClass('is-visible');
})

$('#release-button').click(function(e){
    e.preventDefault();
    $('#release-modal').addClass('is-visible');
})

var payment_received_date = new Pikaday(
{
    field: document.getElementById('id-field-payment_received_date'),
    firstDay: 1,
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});

var payment_release_date = new Pikaday(
{
    field: document.getElementById('id-field-payment_release_date'),
    firstDay: 1,
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});
</script>
@stop