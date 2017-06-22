@section('title')
Delivered Items Inspection
@stop

@section('modal')
    @include('modules.partials.modals.add-issue')
    @include('modules.partials.modals.start-inspection')
    @include('modules.partials.modals.close-inspection')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns utility utility--align-right" >
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button style="margin-right:25px" type="button" class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                @if($data->start_date and !$data->closed_date)
                    <a class="button__options__item" href="#" id="add-issue-button">Add Issue</a>
                    <a class="button__options__item" href="#" id="close-button">Close Inspection</a>
                @endif
                @if(!$data->start_date)
                    <a class="button__options__item" href="#" id="start-button">Start Inspection</a>
                @endif
            </div>
        </button>

            @if($data->start_date and $data->closed_date)
                <a class="button" href="#"><i class="nc-icon-mini tech_print"></i></a>
            @endif
    </div>
</div>

<div class="row">
    <div class="six columns pull-left">
        <h3>Inspection Details</h3>
        <ul>
            <li> <strong>UPR No. :</strong> {{$data->upr_number}} </li>
            <li> <strong>RFQ No. :</strong> {{$data->rfq_number}} </li>
            <li> <strong>Delivery No. :</strong> {{$data->delivery_number}} </li>
            <li> <strong>Status :</strong> {{ucfirst($data->status)}} </li>
            @if($data->started)
                <li> <strong>Start Date :</strong> {{$data->start_date}} </li>
                <li> <strong>Started By :</strong> {{($data->started) ? $data->started->first_name .' '. $data->started->surname : ""}} </li>
            @endif
            @if($data->closed)
                <li> <strong>Close Date :</strong> {{$data->closed_date}} </li>
                <li> <strong>Closed By :</strong> {{($data->closed) ? $data->closed->first_name .' '. $data->closed->surname : ""}} </li>
            @endif
        </ul>
    </div>
    <div class="six columns pull-right">
        <h3>Proponent Details</h3>
        <ul>
            <li> <strong>Name :</strong> {{$supplier->name}} </li>
            <li> <strong>Owner :</strong> {{$supplier->owner}} </li>
            <li> <strong>Address :</strong> {{$supplier->address}} </li>
            <li> <strong>TIN :</strong> {{$supplier->tin}} </li>
            <li> <strong>Cellphone # :</strong> {{$supplier->cell_1}} </li>
            <li> <strong>Phone # :</strong> {{$supplier->phone_1}} </li>
            <li> <strong>FAX :</strong> {{$supplier->fax_1}} </li>
            <li> <strong>Email :</strong> {{$supplier->email_1}} </li>
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