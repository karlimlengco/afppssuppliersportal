@section('title')
Delivered Items Inspection
@stop

@section('modal')
    @include('modules.partials.modals.add-issue')
    @include('modules.partials.modals.start-inspection')
    @include('modules.partials.modals.close-inspection')
    @include('modules.partials.modals.diir-signatory')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns utility utility--align-right" >
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button type="button" class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                <a class="button__options__item" href="#" id="signatories-button">Signatories</a>
                @if($data->start_date and !$data->closed_date)
                    <a class="button__options__item" href="#" id="add-issue-button">Add Issue</a>
                    <a class="button__options__item" href="#" id="close-button">Close Inspection</a>
                @endif
                @if(!$data->start_date)
                    <a class="button__options__item" href="#" id="start-button">Start Inspection</a>
                @endif
                <a id="signatory-button" href="{{route('procurements.unit-purchase-requests.show', $data->upr->id)}}" class="button__options__item" > Unit Purchase Request</a>
                <a id="signatory-button" href="{{route('procurements.blank-rfq.show', $data->rfq->id)}}" class="button__options__item" > Request For Quotation</a>
                <a id="signatory-button" href="{{route('procurements.delivery-orders.show', $data->delivery->id)}}" class="button__options__item" > Delivery</a>
            </div>
        </button>
        <a target="_blank" class="button" href="{{route($printRoute, $data->id)}}"><i class="nc-icon-mini tech_print"></i></a>

        <a href="{{route('procurements.delivered-inspections.logs', $data->id)}}" class="button" tooltip="Logs">
            <i class="nc-icon-mini files_archive-content"></i>
        </a>

        <a href="{{route($editRoute,$data->id)}}" class="button" tooltip="Edit">
            <i class="nc-icon-mini design_pen-01"></i>
        </a>
    </div>
</div>

<div class="data-panel">
    <div class="data-panel__section">
        <h3>Inspection Details</h3>
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">UPR No. :</strong> {{$data->upr_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">RFQ No. :</strong> {{$data->rfq_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Delivery No. :</strong> {{$data->delivery_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Status :</strong> {{ucfirst($data->status)}} </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul class="data-panel__list">

            @if($data->started)
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Start Date :</strong> {{$data->start_date}} </li>
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Started By :</strong> {{($data->started) ? $data->started->first_name .' '. $data->started->surname : ""}} </li>
            @endif
            @if($data->closed)
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Close Date :</strong> {{$data->closed_date}} </li>
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Closed By :</strong> {{($data->closed) ? $data->closed->first_name .' '. $data->closed->surname : ""}} </li>
            @endif
        </ul>
    </div>
    <div class="data-panel__section">
        <h3>Proponent Details</h3>
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Name :</strong> {{$supplier->name}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Owner :</strong> {{$supplier->owner}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Address :</strong> {{$supplier->address}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">TIN :</strong> {{$supplier->tin}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Cellphone # :</strong> {{$supplier->cell_1}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Phone # :</strong> {{$supplier->phone_1}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">FAX :</strong> {{$supplier->fax_1}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Email :</strong> {{$supplier->email_1}} </li>
        </ul>
    </div>
</div>


<div class="row">
    <div class="twelve columns">
        <table class='table' id="item_table">
            <thead>
                <tr>
                    <th>Issues</th>
                    <th>Created By</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->issues as $issue)
                    <tr>
                        <td>{{$issue->issue}}</td>
                        <td>{{($issue->prepared) ? $issue->prepared->first_name .' '. $issue->prepared->surname : ""}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('scripts')

<script type="text/javascript">

    $('#add-issue-button').click(function(e){
        e.preventDefault();
        $('#add-issue-modal').addClass('is-visible');
    })

    $('#signatories-button').click(function(e){
        e.preventDefault();
        $('#signatories-modal').addClass('is-visible');
    })

    $('#start-button').click(function(e){
        e.preventDefault();
        $('#start-modal').addClass('is-visible');
    })

    $('#close-button').click(function(e){
        e.preventDefault();
        $('#close-modal').addClass('is-visible');
    })

    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-date_delivered_to_coa'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    var closed_date = new Pikaday(
    {
        field: document.getElementById('id-field-closed_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });

    var started_date = new Pikaday(
    {
        field: document.getElementById('id-field-start_date'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
    // end datepicker
</script>
@stop