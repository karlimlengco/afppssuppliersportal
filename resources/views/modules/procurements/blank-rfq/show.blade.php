@section('title')
Request For Quotation
@stop

@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
@stop

@section('modal')
    @include('modules.partials.modals.proponents')
    @include('modules.partials.modals.close_rfq')
    @include('modules.partials.create_supplier')
    {{-- @include('modules.partials.modals.invitation') --}}
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


@section('contents')



<div class="message-box message-box--large message-box--success" role="alert">
    <span class="message-box__icon"><i class="nc-icon-outline ui-1_check-circle-08"></i></span>
    <span class="message-box__message">
    Add Proponents
    <br>
    Click option icon to add new proponent </span>
</div>

<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <span class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                {{-- @if($data->status != 'closed') --}}
                <a href="#" class="button__options__item" id="proponent-button">Add Proponents</a>
                {{-- @endif --}}
{{--
                @if($data->status != 'closed')
                    <a href="#" class="button__options__item" id="close-button">Close RFQ</a>
                @endif --}}

                <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" class=" button__options__item">Unit Purchase Request</a>

                <a href="{{route('procurements.blank-rfq.logs', $data->id)}}" class="button__options__item" tooltip="Logs">
                    View Logs
                </a>
                <a class="button__options__item" href="{{route('procurements.unit-purchase-requests.timelines', $data->upr_id)}}">View Timelines</a>

            </div>
        </span>

        <a target="_blank" href="{{route($printRoute,$data->id)}}" class="button" tooltip="Print">
            <i class="nc-icon-mini tech_print"></i>
        </a>

        {{-- @if($data->status != 'closed') --}}
            <a href="{{route($editRoute,$data->id)}}" class="button" tooltip="Edit">
                <i class="nc-icon-mini design_pen-01"></i>
            </a>
        {{-- @endif --}}

        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

    </div>
    <hr>
    <br>
    <div class="twelve columns align-right utility utility--align-right">

        <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="UPR" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
        <span class="button--pull-left" style="padding-top:10px">Go to UPR</span>

        @if($data->status != 'closed')
            <span >Close RFQ</span>
            <a href="#" class="button" id="close-button" tooltip="Next Stage"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @else
            Go to UPR
            <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="UPR" class="button button--pull-right"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @endif
    </div>
</div>

<div class="data-panel">
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">RFQ No. :</strong> {{$data->rfq_number}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">UPR No. :</strong> {{$data->upr_number}} </li>
            @if($data->status == 'closed')
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Closed At :</strong> {{$data->completed_at->format('d M Y')}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Closed Remarks :</strong> {{ $data->close_remarks }} </li>
            @endif
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Working Days :</strong> {{ $data->days }} </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">ABC :</strong> Php {{ formatPrice($data->upr->total_amount) }} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Status :</strong> {{ ucfirst($data->status) }} </li>
            {{-- <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Deadline to Submit :</strong> {{ createCarbon('Y-m-d',$data->upr->philgeps->deadline_rfq)->format('d M Y') }} </li> --}}
            {{-- <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Canvas Opening Time :</strong> {{ $data->upr->philgeps->opening_time }} </li> --}}
    </div>
    <div class="data-panel__section">
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">TransactionDate :</strong> {{ $data->transaction_date->format('d M Y') }} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Remarks :</strong> {{ $data->remarks }} &nbsp;</li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Processed By :</strong> {{($data->processor) ? $data->processor->first_name ." ". $data->processor->surname :""}} </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="six columns">
        <h3>Proponents</h3>
    </div>
</div>

@if(count($data->proponents) != 0)
<div class="row">
    <div class="twelve columns">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Processed Date</th>
                    <th>Bid Amount</th>
                    <th>Prepared By</th>
                    <th>Notes</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->proponents as $proponent)
                <tr>
                    <td>{{($proponent->supplier) ? $proponent->supplier->name :""}}</td>
                    <td>{{$proponent->date_processed}}</td>
                    <td>{{formatPrice($proponent->bid_amount)}}</td>
                    <td>{{($proponent->users) ? $proponent->users->first_name ." ". $proponent->users->surname :""}} </td>
                    <td>{{$proponent->note}}</td>
                    <td>
                        <a href="{{route('procurements.rfq-proponents.show',$proponent->id)}}" tooltip="Attachments"> <span class="nc-icon-glyph ui-1_attach-87"></span> </a>

                        {{-- @if($data->status != 'closed') --}}
                        <a href="{{route('procurements.rfq-proponents.delete',$proponent->id)}}" tooltip="Remove"> <span class="nc-icon-glyph ui-1_trash-simple"></span> </a>

                        <a target="_blank" href="{{route('procurements.rfq-proponents.print',$proponent->id)}}" tooltip="Print"> <span class="nc-icon-glyph tech_print"></span> </a>
                        {{-- @endif --}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@stop

@section('scripts')
<script src="/vendors/timepicker/timepicker.min.js"></script>
<script type="text/javascript">
    // var timepicker1 = new TimePicker(['id-field-opening_time', 'id-field-canvassing_time'], {
    //     lang: 'en',
    //     theme: 'dark'
    // });

    // timepicker1.on('change', function(evt){
    //   var value = (evt.hour || '00') + ':' + (evt.minute || '00');
    //   evt.element.value = value;
    // });

    $('#proponent-button').click(function(e){
        e.preventDefault();
        $('#proponent-modal').addClass('is-visible');
    })
    $('#open_canvass-button').click(function(e){
        e.preventDefault();
        $('#open_canvass-modal').addClass('is-visible');
    })
    $('#philgeps-posting-button').click(function(e){
        e.preventDefault();
        $('#philgeps-posting-modal').addClass('is-visible');
    })
    $('#close-button').click(function(e){
        e.preventDefault();
        $('#close-modal').addClass('is-visible');
    })
    $('#invitation-button').click(function(e){
        e.preventDefault();
        $('#invitation-modal').addClass('is-visible');
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
</script>
@stop