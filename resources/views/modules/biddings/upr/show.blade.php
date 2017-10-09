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
    <div class="modal" id="add-item-modal">
        <div class="modal__dialogue modal__dialogue--round-corner">
             <form method="POST" action="{{route('procurements.upr-items.store', $data->id)}}" accept-charset="UTF-8">
                <button type="button" class="modal__close-button">
                    <i class="nc-icon-outline ui-1_simple-remove"></i>
                </button>

                <div class="moda__dialogue__head">
                    <h1 class="modal__title">Add New Item</h1>
                </div>

                <div class="modal__dialogue__body">
                    {!! Form::selectField('new_account_code', 'Account Code', $accounts) !!}
                    {!! Form::textField('item_description', 'Item Description', null, ['required']) !!}
                    <div class="row">
                      <div class="six columns">
                        {!! Form::textField('quantity', 'Quantity', null, ['required']) !!}
                      </div>
                      <div class="six columns">
                        {!! Form::textField('unit_measurement', 'Unit of Measurement', null, ['required']) !!}
                      </div>
                    </div>
                    <div class="row">
                      <div class="six columns">
                        {!! Form::textField('unit_price', 'Unit Price', null, ['required']) !!}
                      </div>
                    </div>


                    <input name="_token" type="hidden" value="{{ csrf_token() }}">
                    <input name="_method" type="hidden" value="POST">
                </div>

                <div class="modal__dialogue__foot">
                    <button class="button">Proceed</button>
                </div>

            </form>
        </div>
    </div>
    @include('modules.partials.create_supplier')
    @include('modules.partials.modals.request_quotation')
    @include('modules.partials.modals.view-attachments')
    @include('modules.partials.modals.reject-upr')
    @include('modules.partials.modals.dropzone')
    @include('modules.partials.modals.terminate')
    @include('modules.partials.modals.voucher')
    @include('modules.partials.modals.upr-signatory')
    @include('modules.partials.modals.invitation')
    @include('modules.partials.modals.open_canvass')

    {{-- @if($data->status == 'PO Approved' ||  $data->status == 'NTP Accepted') --}}
    @include('modules.partials.modals.ntp')
    @include('modules.partials.modals.create_delivery')
    {{-- @endif --}}

    {{-- @if($data->mode_of_procurement != 'public_bidding' ) --}}
        @include('modules.partials.modals.philgeps_posting')
    {{-- @endif --}}

    @if($data->mode_of_procurement == 'public_bidding')
    @include('modules.partials.bid-modals.dq')
    @include('modules.partials.bid-modals.rfb-process')
    @include('modules.partials.bid-modals.preproc')
    @include('modules.partials.bid-modals.philgeps_posting')
    @include('modules.partials.bid-modals.bid_docs_issue')v
    @include('modules.partials.bid-modals.open-bid')
    @include('modules.partials.bid-modals.post_qual')
    @endif
@stop

@section('contents')
@if($data->status == 'Philgeps Approved' && $data->mode_of_procurement == 'public_bidding'|| $data->status == 'Pre Bid Conference')

<div class="message-box message-box--large message-box--success" role="alert">
    <span class="message-box__icon"><i class="nc-icon-outline ui-1_check-circle-08"></i></span>
    <span class="message-box__message">
    Bid Docs Issuance
    <br>
    Click icon options to add bid docs issuance</span>
</div>
@endif
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
                    @if($data->status == 'upr_processing')
                        <a class="button__options__item" id="process-button" href="#">Process UPR</a>
                        <a class="button__options__item" id="reject-button" href="#">Cancel UPR</a>
                    @endif
                @else


                    @if(strpos($data->status, 'Awarded To') !== false || $data->status == 'Approved NOA')
                        <a class="button__options__item" id="dq-button" href="#">Disqualify Proponent</a>
                    @endif

                    @if($data->status == 'Philgeps Approved' || $data->status == 'Pre Bid Conference')
                        <a class="button__options__item" id="bid-docs-button" href="#">Bid Docs Issuance</a>
                        {{-- <a class="button__options__item" href="{{route('biddings.pre-bids.create-by-upr', $data->id)}}">Pre-Bid Conference</a> --}}
                    @endif
                @endif

                <a class="button__options__item" id="view-attachments-button" href="#">Attachments</a>
                <a class="button__options__item" id="view-attachments-button" href="{{route('procurements.unit-purchase-requests.logs', $data->id)}}">View Logs</a>

                <a class="button__options__item" id="view-attachments-button" href="{{route('procurements.unit-purchase-requests.download-items', $data->id)}}">Download Items</a>

                @if(count($data->delivery_order)  >= 1)
                    <a class="button__options__item" href="{{route('procurements.delivery-orders.lists', $data->id)}}">View Deliveries</a>
                @endif
            </div>
        </button>

        <a href="#" id="attachment-button" class="button" tooltip="Attachments"><i class="nc-icon-mini ui-1_attach-86"></i> </a>
        <a href="#" id="signatory-button" class="button" tooltip="Signatories"><i class="nc-icon-mini business_sign"></i> </a>

        <a target="_blank" href="{{route('procurements.unit-purchase-requests.print', $data->id)}}" class="button" tooltip="Print"> <i class="nc-icon-mini tech_print"></i> </a>


        <a href="{{route($editRoute,$data->id)}}" class="button" tooltip="Edit"> <i class="nc-icon-mini design_pen-01"></i> </a>

        @if($data->mode_of_procurement != 'public_bidding')
            <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        @else
            <a href="{{route('biddings.unit-purchase-requests.index')}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        @endif
    </div>

    @include('modules.procurements.upr.buttons')
</div>



{{-- Proponents --}}
@if($data->status == 'Philgeps Approved' || $data->status == 'Pre Bid Conference')
<h3>Bid Docs Issuance</h3>
<div class="row">
    <div class="twelve columns">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Processed Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->bid_issuances as $proponent)
                <tr>
                    <td>{{($proponent->supplier) ? $proponent->supplier->name :""}}</td>
                    <td>{{$proponent->transaction_date}}</td>
                    <td>
                        <a href="{{route('biddings.bid-docs.delete',$proponent->id)}}" tooltip="Remove"> <span class="nc-icon-glyph ui-1_trash-simple"></span> </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
<br>

<div class="data-panel">
    <div class="data-panel__section">
            <ul class="data-panel__list">
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Project Name:</strong> {{$data->project_name}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">UPR No. :</strong> {{$data->upr_number}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Reference No. :</strong> {{$data->ref_number}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Date Prepared :</strong> {{$data->date_prepared}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Prepared by :</strong> {{($data->users) ? $data->users->first_name ." ". $data->users->surname :""}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Procurement Center / Contracting Office :</strong> {{($data->centers) ? $data->centers->name :""}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Mode of Procurement :</strong> {{($data->modes) ? $data->modes->name :  "Public Bidding" }} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Place Of Delivery :</strong> {{$data->place_of_delivery}} </li>
            </ul>
    </div>
    <div class="data-panel__section">

            <ul  class="data-panel__list">
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Total ABC :</strong> {{number_format($data->total_amount,2)}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Created :</strong> {{$data->created_at}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Procurement Program/Project :</strong> {{($data->types) ? $data->types->description :""}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Units :</strong>    {{($data->unit) ? $data->unit->short_code :""}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Chargeability :</strong> {{($data->charges) ? $data->charges->name :""}} </li>
              {{--   <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Account Code :</strong>
                    {{($data->accounts) ? $data->accounts->new_account_code  :""}}
                    {{($data->accounts) ? "(". $data->accounts->old_account_code .")"  :""}}
                </li> --}}
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
        @if($data->status == 'upr_processing')
        <button id="add-item-button" class="button">ADD ITEM</button>
        @endif
        <table class='table' id="item_table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Account Code</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->items as $item)
                    <tr>
                        <td>{{$item->item_description}}</td> <td>{{($item->accounts) ? $item->accounts->new_account_code." (".$item->accounts->old_account_code .")" : ""}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->unit_measurement}}</td>
                        <td>{{$item->unit_price}}</td>
                        <td>{{formatPrice($item->total_amount)}}</td>
                        <td tooltip="remove">

                        @if($data->status == 'upr_processing')
                          <a href="{{route('procurements.upr-items.destroy', $item->id)}}"><i class="nc-icon-mini ui-1_trash"></i></a>
                        @endif
                        </td>
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
    $('#dq-button').click(function(e){
        e.preventDefault();
        $('#dq-modal').addClass('is-visible');
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

    $('#preproc-button').click(function(e){
        e.preventDefault();
        $('#preproc-modal').addClass('is-visible');
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
    $('#add-item-button').click(function(e){
        e.preventDefault();
        $('#add-item-modal').addClass('is-visible');
    })


    // Create new Supplier
    $proponent_id = $('#id-field-proponent_id').selectize({
        create: true,
        create:function (input){
            $('#create-supplier-modal').addClass('is-visible');
            $('#id-field-name').val(input);
            return true;
        }
    });

    proponent_id        = $proponent_id[0].selectize;

    $(document).on('submit', '#create-supplier-form', function(e){
        e.preventDefault();
        var inputs =  $("#create-supplier-form").serialize();

        $.ajax({
            type: "POST",
            url: '/api/suppliers/store',
            data: inputs,
            success: function(result) {
                proponent_id.addOption({value:result.id, text: result.name});

                $('#create-supplier-modal').removeClass('is-visible');
                $('#create-supplier-form')[0].reset();
            }
        });

    });


    var timepicker = new TimePicker([ 'id-field-canvassing_time','id-field-pp_opening_time' ], {
        lang: 'en',
        theme: 'dark'
    });

    timepicker.on('change', function(evt){
      var value = (evt.hour || '00') + ':' + (evt.minute || '00');
      evt.element.value = value;
    });

    $('.datepicker').pikaday({ firstDay: 1 });


    var resched_date = new Pikaday(
    {
        field: document.getElementById('id-field-resched_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
</script>
@stop