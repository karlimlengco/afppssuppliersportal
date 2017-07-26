@section('title')
Unit Purchase Request
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

@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
@stop

@section('modal')
    @include('modules.partials.modals.request_quotation')
    @include('modules.partials.modals.view-attachments')
    @include('modules.partials.modals.reject-upr')
    @include('modules.partials.modals.dropzone')
    @include('modules.partials.modals.terminate')
    @include('modules.partials.modals.voucher')
    @include('modules.partials.modals.upr-signatory')
    @include('modules.partials.modals.invitation')
    @include('modules.partials.modals.open_canvass')

    @if($data->status == 'PO Approved' ||  $data->status == 'NTP Accepted')
    @include('modules.partials.modals.ntp')
    @include('modules.partials.modals.create_delivery')
    @endif

    @if($data->mode_of_procurement != 'public_bidding' )
        @include('modules.partials.modals.philgeps_posting')
    @endif

    @if($data->mode_of_procurement == 'public_bidding')
    @include('modules.partials.bid-modals.rfb-process')
    @include('modules.partials.bid-modals.philgeps_posting')
    @include('modules.partials.bid-modals.bid_docs_issue')
    @include('modules.partials.bid-modals.open-bid')
    @include('modules.partials.bid-modals.post_qual')
    @endif
@stop

@section('contents')
<div class="row">
    <div class="twelve columns align-right utility utility--align-right">

        <button class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">

                {{-- Always shhow --}}
                @if($data->status != 'pending' && $data->status != 'Cancelled')
                    <a class="button__options__item" href="{{route('procurements.unit-purchase-requests.timelines', $data->id)}}">View Timelines</a>
                @endif

                @if($data->mode_of_procurement != 'public_bidding')
                    @if($data->status == 'Processing RFQ')
                        <a href="#" class="button__options__item" id="invitation-button">Create Invitation</a>
                    @endif
                    @if($data->status == 'Invitation Created' || $data->status == 'Philgeps Need Repost')

                        <a href="#" class="button__options__item" id="philgeps-posting-button">PhilGeps Posting</a>

                    @endif
                    @if($data->status == 'Philgeps Approved')
                        <a href="#" id="open_canvass-button" class="button__options__item">Open Canvass</a>
                    @endif
                    @if($data->status == 'Open Canvass')
                        <a href="{{route('procurements.canvassing.show', $data->canvassing->id)}}" class="button__options__item">View Canvass</a>
                    @endif
                    {{-- Process --}}
                    @if($data->status == 'upr_processing')
                        <a class="button__options__item" id="process-button" href="#">Process UPR</a>
                        <a class="button__options__item" id="reject-button" href="#">Cancel UPR</a>
                    @endif

                @else
                    @if($data->status == 'upr_processing')
                    <a class="button__options__item"  href="{{route('biddings.document-acceptance.create-by-rfq', $data->id)}}">Document Acceptance</a>
                    @endif
                    @if($data->status == 'Document Accepted')
                        <a class="button__options__item" id="itb-button" href="#">Invitation To Bid</a>
                    @endif
                    @if($data->status == 'ITB Created' || $data->status == 'Philgeps Need Repost')
                        <a class="button__options__item" id="biddings-philgeps-posting-button" href="#">PhilGeps Posting</a>
                    @endif
                    @if($data->status == 'Philgeps Approved')
                        <a class="button__options__item" id="bid-docs-button" href="#">Bid Docs Issuance</a>
                        <a class="button__options__item" href="{{route('biddings.pre-bids.create-by-upr', $data->id)}}">Pre-Bid Conference</a>
                    @endif
                    @if($data->status == 'Pre Bid Conference')
                        <a class="button__options__item" id="open-bid-button" href="#">Bid Opening</a>
                    @endif
                    @if($data->status == 'Bid Open')
                        <a class="button__options__item" id="post-qual-button" href="#">Post Qualification</a>
                    @endif
                @endif


                @if(strpos($data->status, 'Awarded To') !== false || $data->status == 'Approved NOA')
                    <a href="{{route('procurements.noa.show', $data->noa->id)}}" class="button__options__item">View NOA</a>
                @endif
                @if($data->status == 'NOA Received')

                    <a href="{{route('procurements.purchase-orders.rfq', $data->id)}}" class="button__options__item">Create PO</a>
                @endif

                @if($data->status == 'PO Created' || $data->status == 'PO Funding Approved' || $data->status == 'PO MFO Approved')
                    <a href="{{route('procurements.purchase-orders.show', $data->purchase_order->id)}}" class="button__options__item">View PO</a>
                @endif

                @if($data->status == 'PO Approved')
                    <a href="#" class="button__options__item" id="ntp-button"> Notice To Proceed</a>
                @endif

                @if($data->status == 'NTP Created')
                    <a href="{{route('procurements.ntp.show', $data->ntp->id)}}" class="button__options__item"> View Notice To Proceed</a>
                @endif

                @if($data->status == 'NTP Accepted')

                    <a class="button__options__item" id="create-delivery-button" href="#">Create Notice Of Delivery</a>
                @endif

                @if($data->status == 'NOD Created' || $data->status == 'Delivery Received')
                <a class="button__options__item"  href="{{route('procurements.delivery-orders.show', $data->delivery_order->id)}}">View Notice Of Delivery</a>
                @endif
                @if($data->status == 'Complete COA Delivery')

                    <a class="button__options__item" href="{{route('procurements.inspection-and-acceptance.create-from-delivery',$data->delivery_order->id)}}">Technical Inspection</a>
                @endif
                @if($data->status == 'Inspection Started')

                    <a class="button__options__item" href="{{route('procurements.inspection-and-acceptance.show',$data->inspections->id)}}"> View Technical Inspection</a>
                @endif
                @if($data->status == 'Inspection Accepted')
                    <a class="button__options__item" href="{{route('procurements.delivery-orders.store-by-dr',$data->delivery_order->id)}}">Delivered Items</a>
                @endif
                @if( $data->status == 'DIIR Created' || $data->status == 'DIIR Started')
                    <a class="button__options__item" href="{{route('procurements.delivered-inspections.show',$data->diir->id)}}">Delivered Items</a>
                @endif
                @if( $data->status == 'DIIR Closed')
                    <a href="#" id="voucher-button" class="button__options__item">Create Voucher</a>
                @endif
                @if( $data->status == 'Voucher Created' || $data->status == 'Voucher Preaudit' || $data->status == 'Voucher Certify'|| $data->status == 'Voucher Journal Entry'|| $data->status == 'Voucher Approved'|| $data->status == 'Voucher Released')
                   <a href="{{route('procurements.vouchers.show', $data->voucher->id)}}" class="button__options__item">View Voucher</a>
                @endif

                <a class="button__options__item" id="view-attachments-button" href="#">Attachments</a>
            </div>
        </button>

        <a href="#" id="attachment-button" class="button" tooltip="Attachments"><i class="nc-icon-mini ui-1_attach-86"></i> </a>
        <a href="#" id="signatory-button" class="button" tooltip="Signatories"><i class="nc-icon-mini business_sign"></i> </a>

        <a target="_blank" href="{{route('procurements.unit-purchase-requests.print', $data->id)}}" class="button" tooltip="Print"> <i class="nc-icon-mini tech_print"></i> </a>

        <a href="{{route('procurements.unit-purchase-requests.logs', $data->id)}}" class="button" tooltip="Logs"> <i class="nc-icon-mini files_archive-content"></i> </a>

        <a href="{{route($editRoute,$data->id)}}" class="button" tooltip="Edit"> <i class="nc-icon-mini design_pen-01"></i> </a>

        @if($data->mode_of_procurement != 'public_bidding')
            <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        @else
            <a href="{{route('biddings.unit-purchase-requests.index')}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        @endif
    </div>
</div>


<div class="data-panel">
    <div class="data-panel__section">
            <ul class="data-panel__list">
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Project Name:</strong> {{$data->project_name}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">UPR No. :</strong> {{$data->upr_number}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Reference No. :</strong> {{$data->ref_number}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Date Prepared :</strong> {{$data->date_prepared}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Prepared by :</strong> {{($data->users) ? $data->users->first_name ." ". $data->users->surname :""}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Procurement Centers :</strong> {{($data->centers) ? $data->centers->name :""}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Mode of Procurement :</strong> {{($data->modes) ? $data->modes->name :  "Public Bidding" }} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Place Of Delivery :</strong> {{$data->place_of_delivery}} </li>
            </ul>
    </div>
    <div class="data-panel__section">

            <ul  class="data-panel__list">
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Total ABC :</strong> {{number_format($data->total_amount,2)}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Created :</strong> {{$data->created_at}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Procurement Type :</strong> {{($data->types) ? $data->types->description :""}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Units :</strong>    {{($data->unit) ? $data->unit->short_code :""}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Chargeability :</strong> {{($data->charges) ? $data->charges->name :""}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Account Code :</strong>
                    {{($data->accounts) ? $data->accounts->new_account_code  :""}}
                    {{($data->accounts) ? "(". $data->accounts->old_account_code .")"  :""}}
                </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Fund Validity :</strong> {{$data->fund_validity}} </li>
            </ul>
    </div>
    <div class="data-panel__section">

            <ul  class="data-panel__list">
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Last Update :</strong> {{$data->updated_at}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Terms of Payment :</strong> {{($data->terms) ? $data->terms->name :""}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Status :</strong> {{ucfirst($data->status)}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">State :</strong> {{ucfirst($data->state)}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Total ABC :</strong> {{number_format($data->total_amount,2)}} </li>
                @if($data->date_processed)
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Date Processed :</strong> {{$data->date_processed}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Processed By :</strong> {{($data->processor) ? $data->processor->first_name ." ". $data->processor->surname :""}} </li>
                @endif
                @if($data->status == 'Cancelled')
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Date Cancelled :</strong> {{$data->cancelled_at}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Cancel reason :</strong> {{$data->cancel_reason}} </li>
                @endif
            </ul>
    </div>
</div>
{{-- Main --}}
<div class="data-panel">
    <div class="data-panel__section">
        <ul  class="data-panel__list">
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Purpose of Purchase :</strong>
                <ul>
                    <li> {{$data->purpose}} </li>
                </ul>
            </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Other Essential Info :</strong>
                <ul>
                    <li> {{$data->other_infos}} </li>
                </ul>
            </li>
        </ul>
    </div>
    @if($data->terminated_date)
    <div class="data-panel__section">
        <ul  class="data-panel__list">
            <li  class="data-panel__list__item">
                <strong  class="data-panel__list__item__label">Terminated Status :</strong>
                {{$data->terminate_status}}
            </li>
            <li  class="data-panel__list__item">
                <strong  class="data-panel__list__item__label">Terminated Date :</strong>
                {{$data->terminated_date}}
            </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul  class="data-panel__list">
            <li  class="data-panel__list__item">
                <strong  class="data-panel__list__item__label">Terminated By :</strong>
                {{($data->terminator) ? $data->terminator->first_name ." ". $data->terminator->surname :""}}
            </li>
            <li  class="data-panel__list__item">
                <strong  class="data-panel__list__item__label">Remarks :</strong>
                {{$data->remarks}}
            </li>
        </ul>
    </div>
    @endif
</div>
<div >
    <div>
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
                        <td>{{$item->item_description}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->unit_measurement}}</td>
                        <td>{{$item->unit_price}}</td>
                        <td>{{formatPrice($item->total_amount)}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
{{-- Main --}}
@stop

@section('scripts')
<script src="/vendors/timepicker/timepicker.min.js"></script>
<script type="text/javascript">

    $('#philgeps-posting-button').click(function(e){
        e.preventDefault();
        $('#philgeps-posting-modal').addClass('is-visible');
    })
    $('#terminate-button').click(function(e){
        e.preventDefault();
        $('#terminate-modal').addClass('is-visible');
    })
    $('#post-qual-button').click(function(e){
        e.preventDefault();
        $('#post-qual-modal').addClass('is-visible');
    })

    $('#open-bid-button').click(function(e){
        e.preventDefault();
        $('#open-bid-modal').addClass('is-visible');
    })
    $('#signatory-button').click(function(e){
        e.preventDefault();
        $('#signatory-modal').addClass('is-visible');
    })
    $('#biddings-philgeps-posting-button').click(function(e){
        e.preventDefault();
        $('#biddings-philgeps-posting-modal').addClass('is-visible');
    })
    $('#bid-docs-button').click(function(e){
        e.preventDefault();
        $('#bid-docs-modal').addClass('is-visible');
    })
    $('#attachment-button').click(function(e){
        e.preventDefault();
        $('#dropzone-modal').addClass('is-visible');
    })
    $('#reject-button').click(function(e){
        e.preventDefault();
        $('#reject-modal').addClass('is-visible');
    })

    $('#itb-button').click(function(e){
        e.preventDefault();
        $('#itb-button-modal').addClass('is-visible');
    })

    $('#process-button').click(function(e){
        e.preventDefault();
        $('#process-modal').addClass('is-visible');
    })

    $('#voucher-button').click(function(e){
        e.preventDefault();
        $('#voucher-modal').addClass('is-visible');
    })

    $('#view-attachments-button').click(function(e){
        e.preventDefault();
        $('#view-attachments-modal').addClass('is-visible');
    })

    $('#invitation-button').click(function(e){
        e.preventDefault();
        $('#invitation-modal').addClass('is-visible');
    })

    $('#open_canvass-button').click(function(e){
        e.preventDefault();
        $('#open_canvass-modal').addClass('is-visible');
    })

    $('#ntp-button').click(function(e){
        e.preventDefault();
        $('#ntp-modal').addClass('is-visible');
    })

    $('#create-delivery-button').click(function(e){
        e.preventDefault();
        $('#create-delivery-modal').addClass('is-visible');
    })


    var timepicker = new TimePicker(['id-field-opening_time', 'id-field-canvassing_time','id-field-pp_opening_time' ], {
        lang: 'en',
        theme: 'dark'
    });

    timepicker.on('change', function(evt){
      var value = (evt.hour || '00') + ':' + (evt.minute || '00');
      evt.element.value = value;
    });

    $('.datepicker').pikaday({ firstDay: 1 });

    $(document).on('change', '#id-field-final_tax', function(e){
        var final_tax  = $("#id-field-final_tax").val();
        var total_amount  = "{{$data->total_amount}}";
        var ft_amount   = total_amount * (final_tax / 100);
        $("#id-field-final_tax_amount").val(ft_amount.toFixed(2));
    });

    $(document).on('change', '#id-field-expanded_witholding_tax', function(e){
        var expanded_witholding_tax  = $("#id-field-expanded_witholding_tax").val();
        var total_amount  = "{{$data->total_amount}}";
        var ewt_amount   = total_amount * (expanded_witholding_tax / 100);
        $("#id-field-ewt_amount").val(ewt_amount.toFixed(2));
    });
</script>
@stop