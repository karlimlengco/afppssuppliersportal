@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3>Unit Purchase Request</h3>
    </div>
    <div class="six columns align-right">
        <button class="button">
            <a href="{{route($createRoute)}}">ADD NEW</a>
        </button>
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

        <table id="datatable-responsive" class="table" >
            <thead>
                <tr>
                    <th>UPR No.</th>
                    <th>Items</th>
                    <th>ABC</th>
                    <th>TYPE</th>
                    <th>AFPPS No.</th>
                    <th>Date Prepared</th>
                    <th>Prepared By</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
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
            {data: 'item_count', name: 'item_count'},
            {data: 'total_amount', name: 'total_amount'},
            {data: 'type', name: 'type'},
            {data: 'afpps_ref_number', name: 'afpps_ref_number'},
            {data: 'date_prepared', name: 'date_prepared'},
            {data: 'full_name', name: 'full_name'},
            {data: 'status', name: 'status'},
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