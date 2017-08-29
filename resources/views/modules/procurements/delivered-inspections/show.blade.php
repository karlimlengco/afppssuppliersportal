@section('title')
Delivered Items Inspection
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
    @include('modules.partials.modals.add-issue')
    @include('modules.partials.modals.start-inspection')
    @include('modules.partials.modals.close-inspection')
    @include('modules.partials.modals.diir-signatory')
    @include('modules.partials.modals.correct-issue')
@stop

@section('contents')


<div class="message-box message-box--large message-box--success" role="alert">
    <span class="message-box__icon"><i class="nc-icon-outline ui-1_check-circle-08"></i></span>
    <span class="message-box__message">
    To Add Issue Click option icon and add issue.
    </span>
</div>


<div class="row">
    <div class="twelve columns utility utility--align-right" >
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button type="button" class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">

                <a href="{{route('procurements.unit-purchase-requests.show', $data->upr->id)}}" class="button__options__item" > Unit Purchase Request</a>

                @if($data->start_date and !$data->closed_date)
                    <a class="button__options__item" href="#" id="add-issue-button">Add Issue</a>
                @endif

                <a target="_blank" href="{{route('procurements.delivered-inspections.print-ris', $data->id)}}" class="button__options__item" > PRINT  RIS</a>
                <a target="_blank" href="{{route('procurements.delivered-inspections.print-ris2', $data->id)}}" class="button__options__item" > PRINT  RIS2</a>
                <a target="_blank" href="{{route('procurements.delivered-inspections.print-rsmi', $data->id)}}" class="button__options__item" > PRINT  RSMI</a>
                <a target="_blank" href="{{route('procurements.delivered-inspections.print-rar', $data->id)}}" class="button__options__item" > PRINT  RAR</a>
                <a target="_blank" href="{{route('procurements.delivered-inspections.print-coi', $data->id)}}" class="button__options__item" > PRINT  COI</a>


                <a href="{{route('procurements.delivered-inspections.logs', $data->id)}}" class="button__options__item" tooltip="Logs">
                    View Logs
                </a>
                <a class="button__options__item" href="{{route('procurements.unit-purchase-requests.timelines', $data->upr_id)}}">View Timelines</a>


            </div>
        </button>

        {{-- <a href="#" id="signatories-button" class="button" tooltip="Signatories"><i class="nc-icon-mini business_sign"></i> </a> --}}

        <a target="_blank" class="button" href="{{route($printRoute, $data->id)}}"><i class="nc-icon-mini tech_print"></i></a>


        <a href="{{route($editRoute,$data->id)}}" class="button" tooltip="Edit">
            <i class="nc-icon-mini design_pen-01"></i>
        </a>
    </div>
    <hr>
    <br>
    <div class="twelve columns align-right utility utility--align-right">
        <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="UPR" class="button button--pull-left"> <i class="nc-icon-mini arrows-1_bold-left"></i> </a>
        <span class="button--pull-left" style="padding-top:10px">Go to UPR</span>

        @if($data->start_date and !$data->closed_date)
            Close Inspection
            <a class="button" tooltip="Close Inspection" id="close-button"  href="#"><i class="nc-icon-mini ui-1_check-bold"></i></a>
        @elseif(!$data->start_date)
            Start Inspection
            <a class="button" tooltip="Start Inspection" id="start-button"  href="#"><i class="nc-icon-mini ui-1_check-bold"></i></a>
        @else
            Go to UPR
            <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="Accept NOA" class="button button--pull-right"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        @endif
    </div>
</div>

<div class="data-panel">
    <div class="data-panel__section">
        <h3>Inspection Details</h3>
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">UPR No. :</strong> {{$data->upr_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Delivery No. :</strong> {{$data->delivery_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Status :</strong> {{ucfirst($data->status)}} </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul class="data-panel__list">

            @if($data->started)
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Start Date :</strong>  @if($data->start_date) {{CreateCarbon('Y-m-d', $data->start_date)->format('d F Y')}}@endif</li>
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Started By :</strong> {{($data->started) ? $data->started->first_name .' '. $data->started->surname : ""}} </li>
            @endif
            @if($data->closed)
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Close Date :</strong> @if($data->closed_date) {{CreateCarbon('Y-m-d', $data->closed_date)->format('d F Y')}}@endif </li>
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
                    <th>Status</th>
                    <th>Remarks</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->issues as $issue)
                    <tr>
                        <td>{{$issue->issue}}</td>
                        <td>{{($issue->prepared) ? $issue->prepared->first_name .' '. $issue->prepared->surname : ""}}</td>
                        <td>{{($issue->is_corrected == 1) ? "Corrected" : ""}}</td>
                        <td>{{$issue->remarks}}</td>
                        <td>
                            @if(!$issue->is_corrected == 1)
                            <a href="#" id="correct-issue-button" class="corrected" data-id="{{$issue->id}}"> <span class="nc-icon-mini ui-1_check-square-09"></span> </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('scripts')

<script type="text/javascript">

    $('#correct-issue-button').click(function(e){
        e.preventDefault();
        $('#correct-issue-modal').addClass('is-visible');
    })
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

    // click award
    $(document).on('click', '.corrected', function(e){
        var id  = $(this).data('id');
        var form = document.getElementById('correct-form').action;
        document.getElementById('correct-form').action = "/procurements/delivered-inspections/corrected/"+id;
    });

</script>
@stop