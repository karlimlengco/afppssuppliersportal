@section('contents')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <div class="row">
                    <div class="col-md-6">
                        <h2>Audit Logs  <small>Lists</small></h2>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                <table id="datatable-responsive" class="table table-hover  dataTable no-footer dtr-inline" >

                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>User</th>
                            <th>App</th>
                            <th>IP Address</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@stop

@section('scripts')
<script type="text/javascript">

    $('#datatable-responsive').DataTable({
        "bLengthChange": false,
        processing: true,
        serverSide: true,
        order: [ [4, 'desc'] ],
        ajax: {
                url: "{{route('datatables.audit-logs')}}",
            },
        columns: [
            {data: 'event', name: 'event'},
            {data: 'fullname', name: 'fullname'},
            {data: 'auditable_type', name: 'auditable_type'},
            {data: 'ip_address', name: 'ip_address'},
            {data: 'created_at', name: 'created_at'},
        ]
    });
</script>
@stop