@section('title')
Aggregate Total
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
    <div class="twelve columns align-right">
        {{-- <div style="display: inline-block">
            <input type="text" id="start" name="date_from" class="input" placeholder="Start Date">
        </div>

        <div style="display: inline-block">
            <input type="text" id="end" name="date_to" class="input" placeholder="End Date">
        </div>
        <button class="button"  ><span class="nc-icon-mini ui-1_zoom"></span></button> --}}
        <a href="/reports/aggregates/download"  class="button" style="margin-bottom:10px"><span class="nc-icon-mini arrows-e_archive-e-download"></span></a>
    </div>
</div>
<div class="table-scrol">
    <table class="table table--with-border ">
        <thead>
            <tr>
                <th rowspan = 2>Mode Of Procurement</th>
                <th colspan = 3>Physical Performance</th>
                <th colspan = 3>Financial Performance</th>
            </tr>
            <tr>
                <th>Target per APP YR</th>
                <th>Actual per APP YR</th>
                <th>%</th>
                <th>Target per APP YR</th>
                <th>Actual per APP YR</th>
                <th>%</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resources as $key => $data)
                <tr>
                    <td>{{$key}}</td>
                    <td>

                        <?php $countTarget = 0; ?>
                        <?php $countActual = 0; ?>
                        @foreach($data as $dat) 
                            @if($dat['status'] != 'completed')
                            <?php $countTarget = $countTarget + 1; ?>
                            @else
                            <?php $countActual = $countActual + 1; ?>
                            @endif
                        @endforeach
                        {{$countTarget}}
                    </td>
                    <td>{{$countActual}}</td>
                    <td>
                            {{ ($countActual/$countTarget)* 100  }}</td>
                    <td>
                        <?php $totalTarget = 0; ?>
                        <?php $totalActual = 0; ?>
                        @foreach($data as $dat) 
                            <?php $totalTarget = $totalTarget + $dat['abc']; ?>
                            <?php $totalActual = $totalActual + $dat['bid_amount']; ?>
                        @endforeach
                        {{formatPrice($totalTarget)}}
                    </td>
                    <td>
                        {{formatPrice($totalActual)}}
                    </td>
                    <td>
                        {{ formatPrice( ($totalActual/$totalTarget)* 100 ) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>

@stop

@section('scripts')
<script type="text/javascript">

    // var ptype = '';

    // table = $('#datatable-responsive').DataTable({
    //     "bLengthChange": false,
    //     processing: true,
    //     serverSide: true,
    //     ajax: {
    //             url: "{{route('datatables.reports.transaction-days')}}",
    //                 data: function(d) {
    //                     d.date_from = $('input[name=date_from]').val();
    //                     d.date_to = $('input[name=date_to]').val();
    //                 }
    //             // data: function (d) {
    //                 // d.search.value = $('#search-table').val();
    //             // }
    //         },
    //     columns: [
    //         {data: 'upr_number', name: 'upr_number'},
    //         {data: 'd_ispq', name: 'd_ispq'},
    //         {data: 'd_philgeps', name: 'd_philgeps'},
    //         {data: 'd_close_blank_rfq', name: 'd_close_blank_rfq'},
    //         {data: 'd_canvass', name: 'd_canvass'},
    //         {data: 'd_noa', name: 'd_noa'},
    //         {data: 'd_noa_approved', name: 'd_noa_approved'},
    //         {data: 'd_noa_accepted', name: 'd_noa_accepted'},
    //         {data: 'd_po_create_date', name: 'd_po_create_date'},
    //         {data: 'd_fund_po_create_date', name: 'd_fund_po_create_date'},
    //         {data: 'd_mfo_released_date', name: 'd_mfo_released_date'},
    //         {data: 'd_coa_approved_date', name: 'd_coa_approved_date'},
    //         {data: 'd_ntp_date', name: 'd_ntp_date'},
    //         {data: 'd_ntp_award_date', name: 'd_ntp_award_date'},
    //         {data: 'd_receive_delivery_date', name: 'd_receive_delivery_date'},
    //         {data: 'd_dr_coa_date', name: 'd_dr_coa_date'},
    //         {data: 'd_dr_inspection', name: 'd_dr_inspection'},
    //         {data: 'd_iar_accepted_date', name: 'd_iar_accepted_date'},
    //         {data: 'd_di_start', name: 'd_di_start'},
    //         {data: 'd_di_close', name: 'd_di_close'},
    //         {data: 'd_vou_start', name: 'd_vou_start'},
    //         {data: 'd_preaudit_date', name: 'd_preaudit_date'},
    //         {data: 'calendar_days', name: 'calendar_days'},
    //     ],
    //     "fnInitComplete": function (oSettings, json) {
    //         $(".previous").html('<i class="nc-icon-outline arrows-1_tail-left"></i>');
    //         $(".next").html('<i class="nc-icon-outline arrows-1_tail-right"></i>');
    //     },
    //     "fnDrawCallback": function (oSettings) {
    //         $(".previous").html('<i class="nc-icon-outline arrows-1_tail-left"></i>');
    //         $(".next").html('<i class="nc-icon-outline arrows-1_tail-right"></i>');
    //     }
    // });

    // table2 = $('#table-bidding').DataTable({
    //     "bLengthChange": false,
    //     processing: true,
    //     serverSide: true,
    //     ajax: {
    //             url: "{{route('datatables.reports.transaction-days','type=bidding')}}",
    //                 data: function(d) {
    //                     d.date_from = $('input[name=date_from]').val();
    //                     d.date_to = $('input[name=date_to]').val();
    //                 }
    //         },
    //     columns: [
    //         {data: 'upr_number', name: 'upr_number'},
    //         {data: 'doc_days', name: 'doc_days'},
    //         {data: 'proc_days', name: 'proc_days'},
    //         {data: 'itb_days', name: 'itb_days'},
    //         {data: 'd_philgeps', name: 'd_philgeps'},
    //         {data: 'prebid_days', name: 'prebid_days'},
    //         {data: 'bid_days', name: 'bid_days'},
    //         {data: 'pq_days', name: 'pq_days'},
    //         {data: 'd_noa', name: 'd_noa'},
    //         {data: 'd_noa_approved', name: 'd_noa_approved'},
    //         {data: 'd_noa_accepted', name: 'd_noa_accepted'},
    //         {data: 'd_po_create_date', name: 'd_po_create_date'},
    //         {data: 'd_fund_po_create_date', name: 'd_fund_po_create_date'},
    //         {data: 'd_mfo_released_date', name: 'd_mfo_released_date'},
    //         {data: 'd_coa_approved_date', name: 'd_coa_approved_date'},
    //         {data: 'd_ntp_date', name: 'd_ntp_date'},
    //         {data: 'd_ntp_award_date', name: 'd_ntp_award_date'},
    //         {data: 'd_receive_delivery_date', name: 'd_receive_delivery_date'},
    //         {data: 'd_dr_coa_date', name: 'd_dr_coa_date'},
    //         {data: 'd_dr_inspection', name: 'd_dr_inspection'},
    //         {data: 'd_iar_accepted_date', name: 'd_iar_accepted_date'},
    //         {data: 'd_di_start', name: 'd_di_start'},
    //         {data: 'd_di_close', name: 'd_di_close'},
    //         {data: 'd_vou_start', name: 'd_vou_start'},
    //         {data: 'd_preaudit_date', name: 'd_preaudit_date'},
    //         {data: 'calendar_days', name: 'calendar_days'},
    //     ],
    //     "fnInitComplete": function (oSettings, json) {
    //         $(".previous").html('<i class="nc-icon-outline arrows-1_tail-left"></i>');
    //         $(".next").html('<i class="nc-icon-outline arrows-1_tail-right"></i>');
    //     },
    //     "fnDrawCallback": function (oSettings) {
    //         $(".previous").html('<i class="nc-icon-outline arrows-1_tail-left"></i>');
    //         $(".next").html('<i class="nc-icon-outline arrows-1_tail-right"></i>');
    //     }
    // });

    // overide datatable filter for custom css
    $('#newForm').keyup(function(){
        table.search($(this).val ()).draw() ;
        table2.search($(this).val ()).draw() ;
    })

    // $('#dateSearch').on('click', function() {
    //     table.draw();
    //     table2.draw();
    // });

    // $('#printme').on('click', function(e){
    //     e.preventDefault();
    //     date_from       =   $('input[name=date_from]').val();
    //     date_to         =   $('input[name=date_to]').val();
    //     table_search    =   $('input[name=table_search]').val();
    //     window.open('/reports/transaction-days/download/'+table_search+'?type='+ptype+'&&date_from='+date_from+'&&date_to='+date_to);
    // });

    $('#alternative').on('click', function(e){
        e.preventDefault();
        $('.button-tab').toggleClass('button-unfocus')
        $('.alternative').show()
        $('.bidding').hide()
        ptype = '';
    });

    $('#bidding').on('click', function(e){
        e.preventDefault();
        $('.button-tab').toggleClass('button-unfocus')
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