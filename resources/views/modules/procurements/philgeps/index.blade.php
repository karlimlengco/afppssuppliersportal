@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3>PhilGeps Posting</h3>
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
            <div class="input-group
                        input-group--has-icon
                        input-group--solid-icon
                        input-group--right-icon">
                <span class="input-group__icon"><i class="nc-icon-outline ui-1_zoom"></i></span>
                <input type="text" class="input" placeholder="Search" id="newForm">
            </div>
        </div>

        <table id="datatable-responsive" class="table" >

            <thead>
                <tr>
                    <th>PhilGeps No.</th>
                    <th>RFQ No.</th>
                    <th>UPR No.</th>
                    <th>PhilGeps Posting</th>
                    <th>Deadline</th>
                    <th>Opening Time</th>
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
                url: "{{route('datatables.procurements.philgeps-posting')}}",
                // data: function (d) {
                    // d.search.value = $('#search-table').val();
                // }
            },
        columns: [
            {data: 'philgeps_number', name: 'philgeps_number'},
            {data: 'rfq_number', name: 'rfq_number'},
            {data: 'upr_number', name: 'upr_number'},
            {data: 'philgeps_posting', name: 'philgeps_posting'},
            {data: 'deadline_rfq', name: 'deadline_rfq'},
            {data: 'opening_time', name: 'opening_time'},
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