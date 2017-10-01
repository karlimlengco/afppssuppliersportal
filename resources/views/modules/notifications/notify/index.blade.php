@section('title')
Notifications
@stop

@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3></h3>
    </div>
</div>

<div class="row">
    <div class="twelve columns">

        <table id="datatable-responsive" class="table" >

            <thead>
                <tr>
                    <th>Event</th>
                    <th>Created</th>
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
        // "bLengthChange": false,
        processing: true,
        serverSide: true,
        order: [ [1, 'desc'] ],
        ajax: {
                url: "{{route('datatables.notifications')}}",
                // data: function (d) {
                    // d.search.value = $('#search-table').val();
                // }
            },
        columns: [
            {data: 'event', name: 'event'},
            {data: 'created_at', name: 'created_at', searchable: false},
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