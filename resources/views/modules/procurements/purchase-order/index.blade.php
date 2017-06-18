@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3>Purchase Orders</h3>
    </div>
    <div class="six columns align-right">
            <a class="button" href="{{route($createRoute)}}">ADD NEW</a>
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
                    <th>RFQ No.</th>
                    <th>Purchase Date</th>
                    <th>Bid Amount</th>
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
        ajax: {
                url: "{{route('datatables.procurements.purchase-orders')}}",
                // data: function (d) {
                    // d.search.value = $('#search-table').val();
                // }
            },
        columns: [
            {data: 'rfq_number', name: 'rfq_number'},
            {data: 'purchase_date', name: 'purchase_date'},
            {data: 'bid_amount', name: 'bid_amount'},
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