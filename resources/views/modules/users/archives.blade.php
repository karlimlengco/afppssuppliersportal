@section('title')
Users
@stop

@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3> </h3>
    </div>
    <div class="six columns utility utility--align-right" >

        <span class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                <a href="{{route('settings.users.index')}}" class="button__options__item">Users</a>
            </div>
        </span>

        <a class="button" href="{{route('settings.users.create')}}" tooltip="Add"><i class="nc-icon-mini ui-1_circle-add"></i></a>
    </div>
</div>

<div class="row">
    <div class="twelve columns">


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
                url: "{{route('datatables.users-trashed')}}",
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