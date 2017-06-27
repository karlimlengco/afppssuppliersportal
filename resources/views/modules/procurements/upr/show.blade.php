@section('title')
Unit Purchase Request
@stop

@section('styles')
<link rel="stylesheet" href="/vendors/timepicker/timepicker.min.css">
@stop

@section('modal')
    @include('modules.partials.modals.view-attachments')
    @include('modules.partials.modals.request_quotation')
    @include('modules.partials.modals.dropzone')
@stop

@section('contents')
<div class="row">
    <div class="twelve columns align-right utility utility--align-right">

        <button class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                <a class="button__options__item" id="view-attachments-button" href="#">View Attachments</a>
                <a class="button__options__item" href="{{route('procurements.unit-purchase-requests.timelines', $data->id)}}">View Timelines</a>
                @if($data->status == 'pending')
                    <a class="button__options__item" id="process-button" href="#">Process</a>
                @endif

                @if(count($data->rfq) != 0)
                    <a href="{{route('procurements.blank-rfq.show', $data->rfq->id)}}" class="button__options__item">Request for Quotation</a>
                @endif

                @if(count($data->philgeps) != 0)
                    <a href="{{route('procurements.philgeps-posting.show', $data->philgeps->id)}}" class="button__options__item">PhilGeps Posting</a>
                @endif
                @if(count($data->invitations) != 0)
                    <a href="{{route('procurements.ispq.edit', $data->invitations->ispq_id)}}" class="button__options__item">Invitation</a>
                @endif

                @if(count($data->canvassing) != 0)
                    <a href="{{route('procurements.canvassing.show', $data->canvassing->id)}}" class="button__options__item">View Canvass</a>
                @endif

                @if(count($data->noa) != 0)
                    <a href="{{route('procurements.noa.show', $data->noa->id)}}" class="button__options__item">View NOA</a>
                @endif

            </div>
        </button>

        <a href="#" id="attachment-button" class="button" tooltip="Attachments">
            <i class="nc-icon-mini ui-1_attach-86"></i>
        </a>

        <a href="#" class="button" tooltip="Print">
            <i class="nc-icon-mini tech_print"></i>
        </a>

        <a href="{{route($editRoute,$data->id)}}" class="button" tooltip="Edit">
            <i class="nc-icon-mini design_pen-01"></i>
        </a>

        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
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
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Place of delivery :</strong> {{($data->centers) ? $data->centers->name :""}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Mode of Procurement :</strong> {{($data->modes) ? $data->modes->name :""}} </li>
            </ul>
    </div>
    <div class="data-panel__section">

            <ul  class="data-panel__list">
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Total ABC :</strong> {{number_format($data->total_amount,2)}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Created :</strong> {{$data->created_at}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Units :</strong>    {{($data->unit) ? $data->unit->name :""}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Chargeability :</strong> {{($data->charges) ? $data->charges->name :""}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Account Code :</strong> {{($data->accounts) ? $data->accounts->new_account_code :""}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Fund Validity :</strong> {{$data->fund_validity}} </li>
            </ul>
    </div>
    <div class="data-panel__section">

            <ul  class="data-panel__list">
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Terms of Payment :</strong> {{($data->terms) ? $data->terms->name :""}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Status :</strong> {{ucfirst($data->status)}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">State :</strong> {{ucfirst($data->state)}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Total ABC :</strong> {{number_format($data->total_amount,2)}} </li>
                @if($data->date_processed)
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Date Processed :</strong> {{$data->date_processed}} </li>
                <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Processed By :</strong> {{($data->processor) ? $data->processor->first_name ." ". $data->processor->surname :""}} </li>
                @endif
            </ul>
    </div>
</div>

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
@stop

@section('scripts')
<script src="/vendors/timepicker/timepicker.min.js"></script>
<script type="text/javascript">

    $('#attachment-button').click(function(e){
        e.preventDefault();
        $('#dropzone-modal').addClass('is-visible');
    })

    $('#process-button').click(function(e){
        e.preventDefault();
        $('#process-modal').addClass('is-visible');
    })

    $('#view-attachments-button').click(function(e){
        e.preventDefault();
        $('#view-attachments-modal').addClass('is-visible');
    })

    var timepicker = new TimePicker('id-field-opening_time', {
        lang: 'en',
        theme: 'dark'
    });

    timepicker.on('change', function(evt){
      var value = (evt.hour || '00') + ':' + (evt.minute || '00');
      evt.element.value = value;
    });

    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-transaction_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-deadline'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
</script>
@stop