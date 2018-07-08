@section('title')
PC/CO Transaction Summary
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
  <psr-all></psr-all>
@stop

@section('scripts')
<script type="text/javascript">
    // var ptype = '';

    // table = $('#datatable-responsive').DataTable({
    //     "bLengthChange": false,
    //     processing: true,
    //     serverSide: true,
    //     ajax: {
    //             url: "{{route('datatables.reports.psr-transactions')}}",
    //                 data: function(d) {
    //                     d.date_from = $('input[name=date_from]').val();
    //                     d.date_to = $('input[name=date_to]').val();
    //                 }
    //         },
    //     columns: [
    //         {data: 'unit_name', name: 'unit_name'},
    //         {data: 'upr', name: 'upr'},
    //         {data: 'ispq', name: 'ispq'},
    //         {data: 'philgeps', name: 'philgeps'},
    //         {data: 'rfq_close', name: 'rfq_close'},
    //         {data: 'canvass', name: 'canvass'},
    //         {data: 'noa', name: 'noa'},
    //         {data: 'noaa', name: 'noaa'},
    //         {data: 'noar', name: 'noar'},
    //         {data: 'po', name: 'po'},
    //         {data: 'po_mfo_received', name: 'po_mfo_received'},
    //         {data: 'po_pcco_received', name: 'po_pcco_received'},
    //         {data: 'po_coa_approved', name: 'po_coa_approved'},
    //         {data: 'ntp', name: 'ntp'},
    //         {data: 'ntpa', name: 'ntpa'},
    //         {data: 'delivery', name: 'delivery'},
    //         {data: 'date_delivered_to_coa', name: 'date_delivered_to_coa'},
    //         {data: 'tiac', name: 'tiac'},
    //         {data: 'coa_inspection', name: 'coa_inspection'},
    //         {data: 'diir_start', name: 'diir_start'},
    //         {data: 'diir_close', name: 'diir_close'},
    //         {data: 'voucher', name: 'voucher'},
    //         {data: 'end_process', name: 'end_process'},
    //     ],
    //     "fnInitComplete": function (oSettings, json) {
    //         $("#datatable-responsive_previous").html('<i class="nc-icon-outline arrows-1_tail-left"></i>');
    //         $("#datatable-responsive_next").html('<i class="nc-icon-outline arrows-1_tail-right"></i>');
    //     }

    // });

    // // public bidding
    // table2 = $('#table-bidding').DataTable({
    //     "bLengthChange": false,
    //     processing: true,
    //     serverSide: true,
    //     ajax: {
    //             url: "{{route('datatables.reports.psr-transactions','type=bidding')}}",
    //                 data: function(d) {
    //                     d.date_from = $('input[name=date_from]').val();
    //                     d.date_to = $('input[name=date_to]').val();
    //                 }
    //         },
    //     columns: [
    //         {data: 'unit_name', name: 'unit_name'},
    //         {data: 'doc', name: 'doc'},
    //         {data: 'pre_proc', name: 'pre_proc'},
    //         {data: 'itb', name: 'itb'},
    //         {data: 'philgeps', name: 'philgeps'},
    //         {data: 'prebid', name: 'prebid'},
    //         {data: 'bidop', name: 'bidop'},
    //         {data: 'pq', name: 'pq'},
    //         {data: 'noa', name: 'noa'},
    //         {data: 'noaa', name: 'noaa'},
    //         {data: 'noar', name: 'noar'},
    //         {data: 'po', name: 'po'},
    //         {data: 'po_mfo_received', name: 'po_mfo_received'},
    //         {data: 'po_pcco_received', name: 'po_pcco_received'},
    //         {data: 'po_coa_approved', name: 'po_coa_approved'},
    //         {data: 'ntp', name: 'ntp'},
    //         {data: 'ntpa', name: 'ntpa'},
    //         {data: 'delivery', name: 'delivery'},
    //         {data: 'date_delivered_to_coa', name: 'date_delivered_to_coa'},
    //         {data: 'tiac', name: 'tiac'},
    //         {data: 'coa_inspection', name: 'coa_inspection'},
    //         {data: 'diir_start', name: 'diir_start'},
    //         {data: 'diir_close', name: 'diir_close'},
    //         {data: 'voucher', name: 'voucher'},
    //         {data: 'end_process', name: 'end_process'},
    //     ],
    //     "fnInitComplete": function (oSettings, json) {
    //         $("#datatable-responsive_previous").html('<i class="nc-icon-outline arrows-1_tail-left"></i>');
    //         $("#datatable-responsive_next").html('<i class="nc-icon-outline arrows-1_tail-right"></i>');
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
    //     window.open('/reports/psr/download/'+table_search+'?type='+ptype+'&&date_from='+date_from+'&&date_to='+date_to);
    // });

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