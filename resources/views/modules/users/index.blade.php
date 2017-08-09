@section('title')
Users
@stop

@section('contents')



<user-lists  v-on:searchingText="searching"></user-lists>

{{-- <div class="row">
    <div class="twelve columns">

        <table id="datatable-responsive" class="table" >
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Full name</th>
                    <th>Designation</th>
                    <th>Unit</th>
                    <th>Email</th>
                    <th>Contact #</th>
                    <th>Gender</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div> --}}


@stop


@section('scripts')
<script type="text/javascript">
    table = $('#datatable-responsive').DataTable({
        processing: true,
        "bLengthChange": false,
        serverSide: true,
        ajax: {
                url: "{{route('datatables.users')}}",
            },
        columns: [
            {data: 'username', name: 'username'},
            {data: 'full_name', name: 'full_name'},
            {data: 'designation', name: 'designation'},
            {data: 'unit_name', name: 'unit_name'},
            {data: 'email', name: 'email'},
            {data: 'contact_number', name: 'contact_number'},
            {data: 'gender', name: 'gender'},
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