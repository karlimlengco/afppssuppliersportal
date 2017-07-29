@section('title')
Transaction Days Report
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
<div class="table-scroll alternative">
    <table id="datatable-responsive" class="table table--with-border ">
        <thead>
            <tr>
                <th>UPR</th>
                <th>Create RFQ</th>
                <th>Close RFQ</th>
                <th>ISPQ</th>
                <th>PhilGeps</th>
                <th>Canvass</th>
                <th>NOA</th>
                <th>NOA Approved</th>
                <th>NOA Accepted</th>
                <th>PO Create</th>
                <th>Funding PO</th>
                <th>Issuance of Certificate</th>
                <th>PO COA Approval</th>
                <th>NTP</th>
                <th>NTPA</th>
                <th>Delivery</th>
                <th>Received DR</th>
                <th>Delivery To COA</th>
                <th>Inspection</th>
                <th>Inspection Acceptance</th>
                <th>Inspection of Delivered Items</th>
                <th>Prepare Certificate of Inspection</th>
                <th>Create Voucher</th>
                <th>PreAudit</th>
                <th>Certify</th>
                <th>JEV</th>
                <th>Voucher Approval</th>
                <th>Release Payment</th>
                <th>Received Payment</th>
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
                <th>NOA Approved</th>
                <th>NOA Accepted</th>
                <th>PO Create</th>
                <th>Funding PO</th>
                <th>Issuance of Certificate</th>
                <th>PO COA Approval</th>
                <th>NTP</th>
                <th>NTPA</th>
                <th>Delivery</th>
                <th>Received DR</th>
                <th>Delivery To COA</th>
                <th>Inspection</th>
                <th>Inspection Acceptance</th>
                <th>Inspection of Delivered Items</th>
                <th>Prepare Certificate of Inspection</th>
                <th>Create Voucher</th>
                <th>PreAudit</th>
                <th>Certify</th>
                <th>JEV</th>
                <th>Voucher Approval</th>
                <th>Release Payment</th>
                <th>Received Payment</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

@stop

@section('scripts')
<script type="text/javascript">

    table = $('#datatable-responsive').DataTable({
        "bLengthChange": false,
        processing: true,
        serverSide: true,
        ajax: {
                url: "{{route('datatables.reports.transaction-days')}}",
                // data: function (d) {
                    // d.search.value = $('#search-table').val();
                // }
            },
        columns: [
            {data: 'upr_number', name: 'upr_number'},
            {data: 'd_blank_rfq', name: 'd_blank_rfq'},
            {data: 'd_close_blank_rfq', name: 'd_close_blank_rfq'},
            {data: 'd_ispq', name: 'd_ispq'},
            {data: 'd_philgeps', name: 'd_philgeps'},
            {data: 'd_canvass', name: 'd_canvass'},
            {data: 'd_noa', name: 'd_noa'},
            {data: 'd_noa_approved', name: 'd_noa_approved'},
            {data: 'd_noa_accepted', name: 'd_noa_accepted'},
            {data: 'd_po_create_date', name: 'd_po_create_date'},
            {data: 'd_fund_po_create_date', name: 'd_fund_po_create_date'},
            {data: 'd_mfo_released_date', name: 'd_mfo_released_date'},
            {data: 'd_coa_approved_date', name: 'd_coa_approved_date'},
            {data: 'd_ntp_date', name: 'd_ntp_date'},
            {data: 'd_ntp_award_date', name: 'd_ntp_award_date'},
            {data: 'd_delivery_date', name: 'd_delivery_date'},
            {data: 'd_receive_delivery_date', name: 'd_receive_delivery_date'},
            {data: 'd_dr_coa_date', name: 'd_dr_coa_date'},
            {data: 'd_dr_inspection', name: 'd_dr_inspection'},
            {data: 'd_iar_accepted_date', name: 'd_iar_accepted_date'},
            {data: 'd_di_start', name: 'd_di_start'},
            {data: 'd_di_close', name: 'd_di_close'},
            {data: 'd_vou_start', name: 'd_vou_start'},
            {data: 'd_preaudit_date', name: 'd_preaudit_date'},
            {data: 'd_certify_date', name: 'd_certify_date'},
            {data: 'd_journal_entry_date', name: 'd_journal_entry_date'},
            {data: 'd_vou_approval_date', name: 'd_vou_approval_date'},
            {data: 'd_vou_release', name: 'd_vou_release'},
            {data: 'd_vou_received', name: 'd_vou_received'},
        ],
        "fnInitComplete": function (oSettings, json) {
            $(".previous").html('<i class="nc-icon-outline arrows-1_tail-left"></i>');
            $(".next").html('<i class="nc-icon-outline arrows-1_tail-right"></i>');
        },
        "fnDrawCallback": function (oSettings) {
            $(".previous").html('<i class="nc-icon-outline arrows-1_tail-left"></i>');
            $(".next").html('<i class="nc-icon-outline arrows-1_tail-right"></i>');
        }
    });

    table = $('#table-bidding').DataTable({
        "bLengthChange": false,
        processing: true,
        serverSide: true,
        ajax: {
                url: "{{route('datatables.reports.transaction-days','type=bidding')}}",
            },
        columns: [
            {data: 'upr_number', name: 'upr_number'},
            {data: 'doc_days', name: 'doc_days'},
            {data: 'itb_days', name: 'itb_days'},
            {data: 'd_philgeps', name: 'd_philgeps'},
            {data: 'prebid_days', name: 'prebid_days'},
            {data: 'bid_days', name: 'bid_days'},
            {data: 'pq_days', name: 'pq_days'},
            {data: 'd_noa', name: 'd_noa'},
            {data: 'd_noa_approved', name: 'd_noa_approved'},
            {data: 'd_noa_accepted', name: 'd_noa_accepted'},
            {data: 'd_po_create_date', name: 'd_po_create_date'},
            {data: 'd_fund_po_create_date', name: 'd_fund_po_create_date'},
            {data: 'd_mfo_released_date', name: 'd_mfo_released_date'},
            {data: 'd_coa_approved_date', name: 'd_coa_approved_date'},
            {data: 'd_ntp_date', name: 'd_ntp_date'},
            {data: 'd_ntp_award_date', name: 'd_ntp_award_date'},
            {data: 'd_delivery_date', name: 'd_delivery_date'},
            {data: 'd_receive_delivery_date', name: 'd_receive_delivery_date'},
            {data: 'd_dr_coa_date', name: 'd_dr_coa_date'},
            {data: 'd_dr_inspection', name: 'd_dr_inspection'},
            {data: 'd_iar_accepted_date', name: 'd_iar_accepted_date'},
            {data: 'd_di_start', name: 'd_di_start'},
            {data: 'd_di_close', name: 'd_di_close'},
            {data: 'd_vou_start', name: 'd_vou_start'},
            {data: 'd_preaudit_date', name: 'd_preaudit_date'},
            {data: 'd_certify_date', name: 'd_certify_date'},
            {data: 'd_journal_entry_date', name: 'd_journal_entry_date'},
            {data: 'd_vou_approval_date', name: 'd_vou_approval_date'},
            {data: 'd_vou_release', name: 'd_vou_release'},
            {data: 'd_vou_received', name: 'd_vou_received'},
        ],
        "fnInitComplete": function (oSettings, json) {
            $(".previous").html('<i class="nc-icon-outline arrows-1_tail-left"></i>');
            $(".next").html('<i class="nc-icon-outline arrows-1_tail-right"></i>');
        },
        "fnDrawCallback": function (oSettings) {
            $(".previous").html('<i class="nc-icon-outline arrows-1_tail-left"></i>');
            $(".next").html('<i class="nc-icon-outline arrows-1_tail-right"></i>');
        }
    });

    // overide datatable filter for custom css
    $('#newForm').keyup(function(){
        table.search($(this).val ()).draw() ;
    })

    $('#printme').on('click', function(e){
        e.preventDefault();
        date_from    =   $('input[name=date_from]').val();
        date_to      =   $('input[name=date_to]').val();
        table_search      =   $('input[name=table_search]').val();
        window.open('/reports/transaction-days/download/'+table_search);
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