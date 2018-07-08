@section('title')
Suppliers
@stop

@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3></h3>
    </div>
    <div class="six columns utility utility--align-right" >

        <span class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                <a href="{{route($draftRoute)}}" class="button__options__item">Drafts</a>
            </div>
        </span>

        <a class="button" href="{{route($createRoute)}}" tooltip="Add"><i class="nc-icon-mini ui-1_circle-add"></i></a>
    </div>
</div>

<div class="row">
    <div class="twelve columns">

        <table id="datatable-responsive" class="table" >

            <thead>
                <tr>
                    <th>Company Name</th>
                    <th>Owner</th>
                    <th>Address</th>
                    <th>DTI</th>
                    <th>Mayors Permit</th>
                    <th>Tax Clearance</th>
                    <th>Philgeps Posting</th>
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
        "bLengthChange": false,
        processing: true,
        serverSide: true,
        ordering: true,
        "order": [[ 0 , "asc" ]],
        ajax: {
                url: "{{route('datatables.settings.suppliers')}}",
                // data: function (d) {
                    // d.search.value = $('#search-table').val();
                // }
            },
        columns: [
            {data: 'name', name: 'name'},
            {data: 'owner', name: 'owner'},
            {data: 'address', name: 'address'},
            {data: 'dti_validity_date', name: 'dti_validity_date'},
            {data: 'mayors_validity_date', name: 'mayors_validity_date'},
            {data: 'tax_validity_date', name: 'tax_validity_date'},
            {data: 'philgeps_validity_date', name: 'philgeps_validity_date'}
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