@section('contents')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <div class="row">
                    <div class="col-md-6">
                        <h2>Roles  <small>Lists</small></h2>
                    </div>
                    <div class="col-md-6">
                        <a href="{{route('settings.roles.create')}}" class="btn btn-primary btn-sm pull-right">ADD NEW</a>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                <table id="datatable-responsive" class="table table-hover  dataTable no-footer dtr-inline" >

                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
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
        ajax: {
                url: "{{route('datatables.roles')}}",
                // data: function (d) {
                    // d.search.value = $('#search-table').val();
                // }
            },
        columns: [
            {data: 'name', name: 'name'},
            {data: 'slug', name: 'slug'},
            {data: 'created_at', name: 'created_at'},
        ]
    });
</script>
@stop