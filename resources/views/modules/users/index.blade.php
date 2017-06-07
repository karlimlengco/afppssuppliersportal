@section('contents')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <div class="row">
                    <div class="col-md-6">
                        <h2>Users  <small>Lists</small></h2>
                    </div>
                    <div class="col-md-6">
                        <a href="{{route('settings.users.create')}}" class="btn btn-primary btn-sm pull-right">ADD NEW</a>
                    </div>
                </div>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <table id="datatable-responsive" class="table table-hover dt-responsive  dataTable no-footer dtr-inline " >
                    <thead>
                        <tr>
                            <th>Avatar</th>
                            <th>Username</th>
                            <th>Full name</th>
                            <th>Email</th>
                            <th>Contact #</th>
                            <th>Gender</th>
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
                url: "{{route('datatables.users')}}",
            },
        columns: [
            {data: 'avatar', name: 'avatar'},
            {data: 'username', name: 'username'},
            {data: 'full_name', name: 'full_name'},
            {data: 'email', name: 'email'},
            {data: 'contact_number', name: 'contact_number'},
            {data: 'gender', name: 'gender'},
        ]
    });
</script>
@stop