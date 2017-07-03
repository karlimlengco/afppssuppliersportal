@section('title')
Vouchers
@stop

@section('modal')
    @include('modules.partials.modals.release-voucher')
    @include('modules.partials.modals.received-voucher')
    @include('modules.partials.modals.preaudit-voucher')
    @include('modules.partials.modals.certify-voucher')
    @include('modules.partials.modals.approval-voucher')
    @include('modules.partials.modals.journal')
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

                @if($data->approval_date && !$data->payment_release_date)
                    <a href="#" id="release-button" class="button__options__item " tooltip="Release Payment"> Release Payment</a>
                @endif

                @if(!$data->payment_received_date and $data->payment_release_date)
                    <a href="#" id="received-button" class="button__options__item" tooltip="Received Payment"> Received Payment</a>
                @endif

                @if(!$data->preaudit_date )
                    <a href="#" id="preaudit-button" class="button__options__item" tooltip="Received Payment"> Conduct Preaudit</a>
                @endif
                @if($data->preaudit_date && !$data->certify_date)
                    <a href="#" id="certify-button" class="button__options__item" tooltip="Received Payment"> Certify Voucher</a>
                @endif
                @if($data->certify_date && !$data->journal_entry_date)
                    <a href="#" id="journal-button" class="button__options__item" tooltip="Received Payment"> Journal Entry</a>
                @endif
                @if($data->journal_entry_date && !$data->approval_date)
                    <a href="#" id="approval-button" class="button__options__item" tooltip="Received Payment"> Approve Voucher</a>
                @endif
            </div>
        </button>
        <a class="button" href="{{route($editRoute,$data->id)}}"><i class="nc-icon-mini design_pen-01"></i></a>
    </div>
</div>


<div class="data-panel">
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">UPR No. :</strong> {{$data->upr_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">RFQ No. :</strong> {{$data->rfq_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Transaction DAte :</strong> {{$data->transaction_date}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">BIR Address. :</strong> {{$data->bir_address}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Final Tax :</strong> {{$data->final_tax}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Expanded Witholding Tax :</strong> {{$data->expanded_witholding_tax}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Prepared By:</strong> {{($data->users) ? $data->users->first_name .' '.$data->users->surname : " "}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Conduct PreAudit :</strong> {{$data->preaudit_date}} </li>
        </ul>
    </div>
    <div class="data-panel__section">
    <h3></h3>
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Certify Date :</strong> {{$data->certify_date}} &nbsp; </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Is Cash Available :</strong> {{($data->is_cash_avail == 1) ? "Yes" : "No"}} &nbsp; </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Subject to authority to debit account:</strong> {{($data->subject_to_authority_to_debit_acc == 1) ? "Yes" : "No"}} &nbsp; </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Supporting Document Complete:</strong> {{($data->documents_completed == 1) ? "Yes" : "No"}} &nbsp; </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Journal Entry Date:</strong> {{$data->journal_entry_date}} &nbsp; </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Number:</strong> {{$data->journal_entry_number}} &nbsp; </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">OR/ Other Documents:</strong> {{$data->or}} &nbsp; </li>
        </ul>
    </div>
    <div class="data-panel__section">
    <h3></h3>
        <ul class="data-panel__list">

                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Approval Date :</strong> {{$data->approval_date}} &nbsp; </li>
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Payment Release Date :</strong> {{$data->payment_release_date}} &nbsp; </li>
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Releaser :</strong> {{($data->releaser) ? $data->releaser->first_name .' '.$data->releaser->surname : " "}} &nbsp; </li>
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Payment Received Date :</strong> {{$data->payment_received_date}} &nbsp; </li>
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Receiver :</strong> {{($data->recevier) ? $data->recevier->first_name .' '.$data->recevier->surname : " "}} &nbsp; </li>
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
$('#certify-button').click(function(e){
    e.preventDefault();
    $('#certify-modal').addClass('is-visible');
})

$('#preaudit-button').click(function(e){
    e.preventDefault();
    $('#preaudit-modal').addClass('is-visible');
})

$('#approval-button').click(function(e){
    e.preventDefault();
    $('#approval-modal').addClass('is-visible');
})

$('#release-button').click(function(e){
    e.preventDefault();
    $('#release-modal').addClass('is-visible');
})
$('#journal-button').click(function(e){
    e.preventDefault();
    $('#journal-modal').addClass('is-visible');
})

var payment_received_date = new Pikaday(
{
    field: document.getElementById('id-field-payment_received_date'),
    firstDay: 1,
    defaultDate: new Date(),
    setDefaultDate: new Date(),
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});

var certify_date = new Pikaday(
{
    field: document.getElementById('id-field-certify_date'),
    firstDay: 1,
    defaultDate: new Date(),
    setDefaultDate: new Date(),
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});

var preaudit_date = new Pikaday(
{
    field: document.getElementById('id-field-preaudit_date'),
    firstDay: 1,
    defaultDate: new Date(),
    setDefaultDate: new Date(),
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});

var payment_release_date = new Pikaday(
{
    field: document.getElementById('id-field-payment_release_date'),
    firstDay: 1,
    defaultDate: new Date(),
    setDefaultDate: new Date(),
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});
var journal_entry_date = new Pikaday(
{
    field: document.getElementById('id-field-journal_entry_date'),
    firstDay: 1,
    defaultDate: new Date(),
    setDefaultDate: new Date(),
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});

var approval_date = new Pikaday(
{
    field: document.getElementById('id-field-approval_date'),
    firstDay: 1,
    defaultDate: new Date(),
    setDefaultDate: new Date(),
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});
</script>
@stop