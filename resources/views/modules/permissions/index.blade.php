@section('title')
Permissions
@stop

@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3> </h3>
    </div>
    <div class="six columns align-right">
        <button class="button">
            <a href="{{route('settings.permissions.create')}}">ADD NEW</a>
        </button>
    </div>
</div>


<div class="row">
   <div class="twelve columns">
        <table id="datatable-responsive" class="table" >
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

@stop

@section('scripts')
<script type="text/javascript">
    table = $('#datatable-responsive').DataTable({
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