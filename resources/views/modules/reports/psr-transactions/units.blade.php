@section('title')
Unit Transaction Summary
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


<div class="row">
    <div class="six columns align-left">
        <button class='button button-tab' id='alternative'>Alternative</button>
        <button class='button button-tab button-unfocus' id='bidding'>Bidding</button>
        {{-- <a href="#" id="printme" class="button" style="margin-bottom:10px">Download</a> --}}
    </div>
    <div class="six columns align-right">
        <div style="display: inline-block">
            <input type="text" id="start" name="date_from" class="input" placeholder="Start Date">
        </div>

        <div style="display: inline-block">
            <input type="text" id="end" name="date_to" class="input" placeholder="End Date">
        </div>
        <button class="button" id="dateSearch"><span class="nc-icon-mini ui-1_zoom"></span></button>
        <a href="#" id="printme" class="button" style="margin-bottom:10px"><span class="nc-icon-mini arrows-e_archive-e-download"></span></a>
    </div>

</div>
<div class="row">
    <div class="twelve columns">
        <div class="table-scroll alternative">
            <table id="datatable-responsive" class="table table--with-border">

                <thead>
                    <tr>
                        <th>Unit</th>
                        <th>UPR</th>
                        <th>ISPQ</th>
                        <th>PhilGeps Posting</th>
                        <th>RFQ</th>
                        <th>Canvassing</th>
                        <th>Prepare NOA</th>
                        <th>Approved NOA</th>
                        <th>Received NOA</th>
                        <th>PO/JO/WO Creation</th>
                        <th>Funding</th>
                        <th>MFO Funding/Obligation</th>
                        <th>PO COA Approval</th>
                        <th>Prepare NTP</th>
                        <th>Received NTP</th>
                        <th>Received Delivery</th>
                        <th>Technical Inspection</th>
                        <th>IAR Acceptance</th>
                        <th>DIIR</th>
                        <th>Prepare Voucher</th>
                        <th>Completed</th>
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
                        <th>Unit</th>
                        <th>Document Acceptance (BAC)</th>
                        <th>Pre Proc (BAC)</th>
                        <th>Invitation to BId (BAC)</th>
                        <th>PhilGeps Posting (BAC)</th>
                        <th>Pre Bid Conference (BAC)</th>
                        <th>SOBE (BAC)</th>
                        <th>POST QUAL (BAC)</th>
                        <th>Prepare NOA (BAC)</th>
                        <th>Approved NOA (PCCO)</th>
                        <th>Received NOA (PCCO)</th>
                        <th>Contract Creation (PCCO)</th>
                        <th>Contract Funding (PCCO)</th>
                        <th>MFO Funding/Obligation (PCCO)</th>
                        <th>Contract COA Approval (PCCO)</th>
                        <th>Prepare NTP</th>
                        <th>Received NTP</th>
                        <th>Received Delivery</th>
                        <th>Technical Inspection</th>
                        <th>IAR Acceptance</th>
                        <th>DIIR</th>
                        <th>Prepare Voucher</th>
                        <th>Completed</th>
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
    var ptype = '';

    table = $('#datatable-responsive').DataTable({
        "bLengthChange": false,
        processing: true,
        serverSide: true,
        ajax: {
                url: "{{route('datatables.reports.unit-psr-transactions')}}",
                    data: function(d) {
                        d.date_from = $('input[name=date_from]').val();
                        d.date_to = $('input[name=date_to]').val();
                    }
            },
        columns: [
            {data: 'unit_name', name: 'unit_name'},
            {data: 'upr', name: 'upr'},
            {data: 'ispq', name: 'ispq'},
            {data: 'philgeps', name: 'philgeps'},
            {data: 'rfq', name: 'rfq'},
            {data: 'canvass', name: 'canvass'},
            {data: 'noa', name: 'noa'},
            {data: 'noaa', name: 'noaa'},
            {data: 'noar', name: 'noar'},
            {data: 'po', name: 'po'},
            {data: 'po_mfo_received', name: 'po_mfo_received'},
            {data: 'po_pcco_received', name: 'po_pcco_received'},
            {data: 'po_coa_approved', name: 'po_coa_approved'},
            {data: 'ntp', name: 'ntp'},
            {data: 'ntpa', name: 'ntpa'},
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
    table2 = $('#table-bidding').DataTable({
        "bLengthChange": false,
        processing: true,
        serverSide: true,
        ajax: {
                url: "{{route('datatables.reports.unit-psr-transactions','type=bidding')}}",
                    data: function(d) {
                        d.date_from = $('input[name=date_from]').val();
                        d.date_to = $('input[name=date_to]').val();
                    }
            },
        columns: [
            {data: 'unit_name', name: 'unit_name'},
            {data: 'doc', name: 'doc'},
            {data: 'pre_proc', name: 'pre_proc'},
            {data: 'itb', name: 'itb'},
            {data: 'philgeps', name: 'philgeps'},
            {data: 'prebid', name: 'prebid'},
            {data: 'bidop', name: 'bidop'},
            {data: 'pq', name: 'pq'},
            {data: 'noa', name: 'noa'},
            {data: 'noaa', name: 'noaa'},
            {data: 'noar', name: 'noar'},
            {data: 'po', name: 'po'},
            {data: 'po_mfo_received', name: 'po_mfo_received'},
            {data: 'po_pcco_received', name: 'po_pcco_received'},
            {data: 'po_coa_approved', name: 'po_coa_approved'},
            {data: 'ntp', name: 'ntp'},
            {data: 'ntpa', name: 'ntpa'},
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
        table.search($(this).val ()).draw() ;
        table2.search($(this).val ()).draw() ;
    })

    $('#dateSearch').on('click', function() {
        table.draw();
        table2.draw();
    });

    $('#printme').on('click', function(e){
        e.preventDefault();
        date_from       =   $('input[name=date_from]').val();
        date_to         =   $('input[name=date_to]').val();
        table_search    =   $('input[name=table_search]').val();
        window.open('/reports/unit-psr/download/'+table_search+'?type='+ptype+'&&date_from='+date_from+'&&date_to='+date_to);
    });

    $('#alternative').on('click', function(e){
        e.preventDefault();
        $('.button').toggleClass('button-unfocus')
        $('.alternative').show()
        $('.bidding').hide()
        ptype = '';
    });

    $('#bidding').on('click', function(e){
        e.preventDefault();
        $('.button').toggleClass('button-unfocus')
        $('.alternative').hide()
        $('.bidding').removeClass('hidden')
        $('.bidding').show()
        ptype = 'bidding';
    });


    var startDate,
        endDate,
        updateStartDate = function() {
            startPicker.setStartRange(startDate);
            endPicker.setStartRange(startDate);
            endPicker.setMinDate(startDate);
        },
        updateEndDate = function() {
            startPicker.setEndRange(endDate);
            startPicker.setMaxDate(endDate);
            endPicker.setEndRange(endDate);
        },
        startPicker = new Pikaday({
            field: document.getElementById('start'),
            minDate: new Date(2012, 12, 31),
            onSelect: function() {
                startDate = this.getDate();
                updateStartDate();
            }
        }),
        endPicker = new Pikaday({
            field: document.getElementById('end'),
            minDate: new Date(2012, 12, 31),
            onSelect: function() {
                endDate = this.getDate();
                updateEndDate();
            }
        }),
        _startDate = startPicker.getDate(),
        _endDate = endPicker.getDate();

        if (_startDate) {
            startDate = _startDate;
            updateStartDate();
        }

        if (_endDate) {
            endDate = _endDate;
            updateEndDate();
        }
</script>
@stop