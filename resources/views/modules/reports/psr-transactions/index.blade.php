@section('title')
Transaction Summary
@stop

@section('contents')

<a href="#" id="printme" class="button" style="margin-bottom:10px">Excel</a>

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
        <div class="table-scroll">
            <table id="datatable-responsive" class="table table--with-border">

                <thead>
                    <tr>
                        <th>PC/CO</th>
                        <th>UPR</th>
                        <th>RFQ</th>
                        <th>RFQ Closed</th>
                        <th>PhilGeps</th>
                        <th>ISPQ</th>
                        <th>Canvass</th>
                        <th>NOA</th>
                        <th>NOAA</th>
                        <th>PO</th>
                        <th>MFO OB</th>
                        <th>ACCTG OB</th>
                        <th>MFO Received</th>
                        <th>ACCTG Received</th>
                        <th>COA Approved</th>
                        <th>NTP</th>
                        <th>NTPA</th>
                        <th>NOD</th>
                        <th>Delivery</th>
                        <th>TIAC</th>
                        <th>COA Delivery</th>
                        <th>DIIR</th>
                        <th>Voucher</th>
                        <th>End</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
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
                url: "{{route('datatables.reports.psr-transactions')}}",
                // data: function (d) {
                    // d.search.value = $('#search-table').val();
                // }
            },
        columns: [
            {data: 'unit_name', name: 'unit_name'},
            {data: 'upr', name: 'upr'},
            {data: 'rfq', name: 'rfq'},
            {data: 'rfq_close', name: 'rfq_close'},
            {data: 'philgeps', name: 'philgeps'},
            {data: 'ispq', name: 'ispq'},
            {data: 'canvass', name: 'canvass'},
            {data: 'noa', name: 'noa'},
            {data: 'noaa', name: 'noaa'},
            {data: 'po', name: 'po'},
            {data: 'po_mfo_released', name: 'po_mfo_released'},
            {data: 'po_pcco_released', name: 'po_pcco_released'},
            {data: 'po_mfo_received', name: 'po_mfo_received'},
            {data: 'po_pcco_received', name: 'po_pcco_received'},
            {data: 'po_coa_approved', name: 'po_coa_approved'},
            {data: 'ntp', name: 'ntp'},
            {data: 'ntpa', name: 'ntpa'},
            {data: 'nod', name: 'nod'},
            {data: 'delivery', name: 'delivery'},
            {data: 'tiac', name: 'tiac'},
            {data: 'coa_inspection', name: 'coa_inspection'},
            {data: 'diir', name: 'diir'},
            {data: 'voucher', name: 'voucher'},
            {data: 'end_process', name: 'end_process'},
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

    $('#printme').on('click', function(e){
        e.preventDefault();
        date_from    =   $('input[name=date_from]').val();
        date_to      =   $('input[name=date_to]').val();
        table_search      =   $('input[name=table_search]').val();
        window.open('/reports/psr/download/'+table_search);
    });
</script>
@stop