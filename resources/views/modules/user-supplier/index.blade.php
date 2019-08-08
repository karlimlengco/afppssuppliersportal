@section('title')
Users (Supliers)
@stop

@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3></h3>
    </div>
    <div class="six columns utility utility--align-right" >
        <a class="button" href="{{route('settings.user-suppliers.create')}}" tooltip="Add"><i class="nc-icon-mini ui-1_circle-add"></i></a>
    </div>
</div>


{{-- <user-lists  v-on:searchingText="searching"></user-lists> --}}

<div class="row">
    <div class="twelve columns">

        <table id="datatable-responsive" class="table" >
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Company</th>
                    <th>Email</th>
                    <th>Contact #</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $data)
                <tr>
                    <td>
                        <a href="{{route( 'settings.user-suppliers.show',[$data->username] )}}">
                        {{$data->username}}
                        </a>
                    </td>
                    <td>{{$data->first_name}}</td>
                    <td>{{$data->email}}</td>
                    <td>{{$data->contact_number}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <?php echo $suppliers->render(); ?>
    </div>
</div>


@stop


@section('scripts')
{{-- <script type="text/javascript">
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
<div class="row">
    <div class="six columns align-left">
        <h3></h3>
    </div>
    <div class="six columns utility utility--align-right" >
        <a class="button" href="{{route($createRoute)}}" tooltip="Add"><i class="nc-icon-mini ui-1_circle-add"></i></a>
    </div>
</div> --}}
@stop