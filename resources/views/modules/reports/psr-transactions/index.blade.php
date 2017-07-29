@section('title')
Transaction Summary
@stop

@section('styles')
<style type="text/css">
    .hidden, .dataTables_filter{
        display: none!important;
        visibility: hidden;
    }
    .hidden #datatable-responsive_wrapper{
        display: none!important;
        visibility: hidden;
    }
</style>
@stop

@section('contents')

{{-- <a href="#" id="printme" class="button" style="margin-bottom:10px">Excel</a> --}}


<button class='button' id='alternative'>Alternative</button>
<button class='button button-unfocus' id='bidding'>Bidding</button>

<div class="row">
    <div class="twelve columns">
        <div class="table-scroll alternative">
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

        <div class="table-scroll bidding hidden">
            <table id="table-bidding"  class="table table--with-border ">
                <thead>
                    <tr>
                        <th>UPR</th>
                        <th>Document Acceptance</th>
                        <th>Invitation To Bid</th>
                        <th>Philgeps</th>
                        <th>Pre Bid</th>
                        <th>Bid Opening</th>
                        <th>Post Qual</th>
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

    // public bidding
    table = $('#table-bidding').DataTable({
        "bLengthChange": false,
        processing: true,
        serverSide: true,
        ajax: {
                url: "{{route('datatables.reports.psr-transactions','type=bidding')}}",
            },
        columns: [
            {data: 'unit_name', name: 'unit_name'},
            {data: 'doc', name: 'doc'},
            {data: 'itb', name: 'itb'},
            {data: 'philgeps', name: 'philgeps'},
            {data: 'prebid', name: 'prebid'},
            {data: 'bidop', name: 'bidop'},
            {data: 'pq', name: 'pq'},
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

    $('#alternative').on('click', function(e){
        e.preventDefault();
        $('.button').toggleClass('button-unfocus')
        $('.alternative').show()
        $('.bidding').hide()
    });

    $('#bidding').on('click', function(e){
        e.preventDefault();
        $('.button').toggleClass('button-unfocus')
        $('.alternative').hide()
        $('.bidding').removeClass('hidden')
        $('.bidding').show()
    });
</script>
@stop