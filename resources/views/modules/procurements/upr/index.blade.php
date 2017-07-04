@section('title')
Unit Purchase Request
@stop

@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3></h3>
    </div>
    <div class="six columns utility utility--align-right" >

        <button class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                <a href="{{route($importRoute)}}" class="button__options__item">Import UPR</a>
            </div>
        </button>

        <a class="button" href="{{route($createRoute)}}" tooltip="Add"><i class="nc-icon-mini ui-1_circle-add"></i></a>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        <div class="form-group">
            <div class="input-group input-group--has-icon input-group--solid-icon input-group--right-icon">
                <span class="input-group__icon"><i class="nc-icon-outline ui-1_zoom"></i></span>
                <input type="text" class="input" placeholder="Search" id="newForm">
            </div>
        </div>

        {{-- <div class="table-scroll"> --}}
            <table id="datatable-responsive" class="table ">
            <thead>
                <tr>
                    <th>UPR No.</th>
                    <th>Ref No.</th>
                    <th>Project</th>
                    <th>ABC</th>
                    <th>TYPE</th>
                    <th>Status</th>
                    <th>State</th>
                    <th>Calendar Days</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        {{-- </div> --}}
    </div>
</div>

@stop

@section('scripts')
<script type="text/javascript">

    table = $('#datatable-responsive').DataTable({
        "bLengthChange": false,
        processing: true,
        serverSide: true,
        ajax: {
                url: "{{route('datatables.procurements.unit-purchase-request')}}",
                // data: function (d) {
                    // d.search.value = $('#search-table').val();
                // }
            },
        columns: [
            {data: 'upr_number', name: 'upr_number'},
            {data: 'ref_number', name: 'ref_number'},
            {data: 'project_name', name: 'project_name'},
            {data: 'total_amount', name: 'total_amount'},
            {data: 'type', name: 'type'},
            {data: 'status', name: 'status'},
            {data: 'state', name: 'state'},
            {data: 'calendar_days', name: 'calendar_days'},
        ],
        "fnInitComplete": function (oSettings, json) {
            $("#datatable-responsive_previous").html('<i class="nc-icon-outline arrows-1_tail-left"></i>');
            $("#datatable-responsive_next").html('<i class="nc-icon-outline arrows-1_tail-right"></i>');
        }

    });

    // overide datatable filter for custom css
    $('#newForm').keyup(function(){
          table.search($(this).val()).draw() ;
    })
</script>
@stop