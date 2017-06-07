@section('contents')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">

                <div class="row">
                    <div class="col-md-6">
                        <h2>Permissions  <small>Lists</small></h2>
                    </div>
                    <div class="col-md-6">
                        <a href="{{route('settings.permissions.create')}}" class="btn btn-success btn-sm pull-right">ADD NEW</a>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <table id="datatable-responsive" class="table table-hover dataTable no-footer dtr-inline" >
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Permission</th>
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
        processing: true,
        "bLengthChange": false,
        serverSide: true,
        ajax: {
                url: "{{route('datatables.permissions')}}",
            },
        columns: [
            {data: 'permission', name: 'permission'},
            {data: 'description', name: 'description'},
            {data: 'created_at', name: 'created_at'},
        ]
    });
</script>
@stop