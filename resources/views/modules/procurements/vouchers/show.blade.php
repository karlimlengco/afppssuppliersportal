@section('title')
Vouchers
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
    @include('modules.partials.modals.release-voucher')
    @include('modules.partials.modals.prepare-lddap')
    @include('modules.partials.modals.received-voucher')
    @include('modules.partials.modals.preaudit-voucher')
    @include('modules.partials.modals.certify-voucher')
    @include('modules.partials.modals.approval-voucher')
    @include('modules.partials.modals.journal')
    @include('modules.partials.modals.voucher-signatory')
    @include('modules.partials.modals.voucher-counter-signing')
@stop

@section('contents')
@if($data->preaudit_date)
{{--     <div class="message-box message-box--large message-box--success" role="alert">
        <span class="message-box__icon"><i class="nc-icon-outline ui-1_check-circle-08"></i></span>
        <span class="message-box__message">
            UPR is marked Completed, if you want to continue process click options.
            <br>
        </span>
    </div> --}}
@endif

<div class="row">
    <div class="twelve columns utility utility--align-right" >
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>


        <span class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                <a class="button__options__item" href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}">Unit of Purchase Request</a>

                <a target="_blank" class="button__options__item" href="{{route('procurements.vouchers.print', $data->id)}}">Print With TAX</a>
                <a target="_blank" class="button__options__item" href="{{route('procurements.vouchers.print2', $data->id)}}">Print With TAX (Form 2)</a>

                <a target="_blank" class="button__options__item" href="{{route('procurements.vouchers.print-wotax', $data->id)}}">Print Without TAX</a>

                @if($data->approval_date && !$data->payment_release_date)
                    {{-- <a href="#" id="release-button" class="button__options__item " tooltip="Release Payment"> Release Payment</a> --}}
                @endif

         {{--        @if(!$data->payment_received_date and $data->payment_release_date)
                    <a href="#" id="received-button" class="button__options__item" tooltip="Received Payment"> Received Payment</a>
                @endif --}}


                @if($data->preaudit_date && !$data->certify_date)
                    {{-- <a href="#" id="certify-button" class="button__options__item" tooltip="Certify Voucher"> Certify Voucher</a> --}}
                @endif
                @if($data->certify_date && !$data->journal_entry_date)
                    {{-- <a href="#" id="journal-button" class="button__options__item" tooltip="Journal Entry"> Journal Entry</a> --}}
                @endif
                @if($data->journal_entry_date && !$data->approval_date)
                    {{-- <a href="#" id="approval-button" class="button__options__item" tooltip="Approve Voucher"> Approve Voucher</a> --}}
                @endif
                <a class="button__options__item" href="{{route('procurements.unit-purchase-requests.timelines', $data->upr_id)}}">View Timelines</a>
                <a class="button__options__item" href="{{route('procurements.vouchers.logs', $data->id)}}">View Logs</a>

            </div>
        </span>

        {{-- <a href="#" id="signatory-button" class="button" tooltip="Signatories"><i class="nc-icon-mini business_sign"></i> </a> --}}
      {{--   <a target="_blank" href="{{route('procurements.vouchers.print', $data->id)}}" class="button" tooltip="Print">
            <i class="nc-icon-mini tech_print"></i>
        </a> --}}
       {{--  <a href="{{route('procurements.vouchers.logs', $data->id)}}" class="button" tooltip="Logs">
            <i class="nc-icon-mini files_archive-content"></i>
        </a> --}}
        <a class="button" href="{{route($editRoute,$data->id)}}"><i class="nc-icon-mini design_pen-01"></i></a>
    </div>

    <hr>
    <br>
    <div class="twelve columns align-right utility utility--align-right">
        <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="UPR" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
        <span class="button--pull-left" style="padding-top:10px">Go to UPR</span>
        @if(!$data->certify_date)
            Certify Cash
            <a href="#" id="certify-button" class="button" tooltip="Certify Voucher"> <i class="nc-icon-mini arrows-1_bold-right"></i></a>
            {{-- <a class="button" tooltip="Conduct Preaudit" id="preaudit-button"  href="#"><i class="nc-icon-mini ui-1_check-bold"></i></a> --}}
        @elseif($data->certify_date && !$data->journal_entry_date)
            Accounting Journal Entry
            <a href="#" id="journal-button" class="button" tooltip="Journal Entry"> <i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @elseif($data->journal_entry_date && !$data->approval_date)
            Approve Voucher
            <a href="#" id="approval-button" class="button" tooltip="Approve Voucher"> <i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @elseif($data->approval_date && !$data->preaudit_date)
            Conduct Preaudit
            <a href="#" id="preaudit-button" class="button" tooltip="Conduct Preaudit"> <i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @elseif($data->preaudit_date && !$data->prepare_cheque_date)
            Prepare LDDAP-ADA
            <a href="#" id="lddap-button" class="button" tooltip="Prepare LDDAP-ADA"> <i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @elseif($data->prepare_cheque_date && !$data->payment_release_date)
            Release LDDAP-ADA
            <a href="#" id="release-button" class="button" tooltip="Prepare LDDAP-ADA"> <i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @elseif($data->payment_release_date && !$data->counter_sign_date)
            Counter Signing of Cheque
            <a href="#" id="counter-button" class="button" tooltip="Counter Signing of Cheque"> <i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @elseif($data->counter_sign_date && !$data->payment_received_date)
            Receive Payment
            <a href="#" id="received-button" class="button" tooltip="Receive Payment"> <i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @else
            Go to UPR
            <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="Accept NOA" class="button button--pull-right"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @endif
    </div>
</div>


<div class="data-panel">
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">UPR No. :</strong> {{$data->upr_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Transaction DAte :</strong>  @if($data->transaction_date) {{CreateCarbon('Y-m-d', $data->transaction_date)->format('d F Y')}}@endif</li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">BIR Address. :</strong> {{$data->bir_address}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Final Tax :</strong> {{$data->final_tax}} &nbsp;</li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Expanded Witholding Tax :</strong> {{$data->expanded_witholding_tax}} &nbsp;</li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Prepared By:</strong> {{($data->users) ? $data->users->first_name .' '.$data->users->surname : " "}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Conduct PreAudit :</strong>  @if($data->preaudit_date) {{CreateCarbon('Y-m-d', $data->preaudit_date)->format('d F Y')}}@endif</li>
        </ul>
    </div>
    <div class="data-panel__section">
    <h3></h3>
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Certify Date :</strong> @if($data->certify_date) {{CreateCarbon('Y-m-d', $data->certify_date)->format('d F Y')}}@endif &nbsp; </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Is Cash Available :</strong> {{($data->is_cash_avail == 1) ? "Yes" : "No"}} &nbsp; </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Subject to authority to debit account:</strong> {{($data->subject_to_authority_to_debit_acc == 1) ? "Yes" : "No"}} &nbsp; </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Supporting Document Complete:</strong> {{($data->documents_completed == 1) ? "Yes" : "No"}} &nbsp; </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Journal Entry Date:</strong>  @if($data->journal_entry_date) {{CreateCarbon('Y-m-d', $data->journal_entry_date)->format('d F Y')}}@endif &nbsp; </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Number:</strong> {{$data->journal_entry_number}} &nbsp; </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">OR/ Other Documents:</strong> {{$data->or}} &nbsp; </li>
        </ul>
    </div>
    <div class="data-panel__section">
    <h3></h3>
        <ul class="data-panel__list">

                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Approval Date :</strong> @if($data->approval_date) {{CreateCarbon('Y-m-d', $data->approval_date)->format('d F Y')}}@endif &nbsp; </li>
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Payment Release Date :</strong> @if($data->payment_release_date) {{CreateCarbon('Y-m-d', $data->payment_release_date)->format('d F Y')}}@endif &nbsp; </li>
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Releaser :</strong> {{($data->releaser) ? $data->releaser->first_name .' '.$data->releaser->surname : " "}} &nbsp; </li>
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Payment Received Date :</strong> @if($data->payment_received_date) {{CreateCarbon('Y-m-d', $data->payment_received_date)->format('d F Y')}}@endif &nbsp; </li>
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Receiver :</strong> {{($data->recevier) ? $data->recevier->first_name .' '.$data->recevier->surname : " "}} &nbsp; </li>

                {{-- <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Penalty Days :</strong>  &nbsp; </li> --}}

                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Penalty Amount : </strong>  Php {{ formatPrice($penalty_amount)}} &nbsp; </li>
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label"> Supliers Address : </strong>  Php {{ ($data->suppliers_address) ? $data->suppliers_address : ""}} &nbsp; </li>
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
$('#lddap-button').click(function(e){
    e.preventDefault();
    $('#lddap-modal').addClass('is-visible');
})
$('#counter-button').click(function(e){
    e.preventDefault();
    $('#counter-modal').addClass('is-visible');
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
$('#signatory-button').click(function(e){
    e.preventDefault();
    $('#signatory-modal').addClass('is-visible');
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
var payment_date = new Pikaday(
{
    field: document.getElementById('id-field-payment_date'),
    firstDay: 1,
    defaultDate: new Date(),
    setDefaultDate: new Date(),
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});
</script>
@stop