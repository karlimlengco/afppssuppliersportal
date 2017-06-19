@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3>Users</h3>
    </div>
    <div class="six columns align-right">
            <a class="button" href="{{route('settings.users.create')}}">ADD NEW</a>
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