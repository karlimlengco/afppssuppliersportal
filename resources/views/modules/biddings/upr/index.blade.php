@section('title')
Unit Purchase Request
@stop


@section('breadcrumbs')

    @if(isset($breadcrumbs))
      @foreach($breadcrumbs as $route => $crumb)
        @if($crumb->hasLink())
        <a href="{{ $crumb->link() }}" class="topbar__breadcrumbs__item">{{ $crumb->title() }}</a>
        @else
        <a href="#" class="topbar__breadcrumbs__item">{{ $crumb->title() }}</a>
        @endif
      @endforeach
    @else
    <li><a href="#">Application</a></li>
    @endif

@stop

@section('contents')
<div class="row">
    <div class="six columns ">
        {{-- <a class="button" href="{{route('procurements.unit-purchase-requests.view-cancel')}}" tooltip="View All Cancelled"><i class="nc-icon-mini ui-1_circle-remove"></i></a> --}}
        <a  class="button" href="{{route('biddings.unit-purchase-requests.view-cancel')}}"> View All Cancelled UPR</a>
    </div>
    <div class="six columns align-right" >

        <a class="button" href="{{route($createRoute)}}" tooltip="Add UPR">Add UPR</a>
        <a class="button" href="{{route($importRoute)}}" tooltip="Import UPR">Import UPR</a>
    </div>
</div>

<div class="row">
    <br>
    <h3><span style="border-bottom:2px solid black">List of Unit Purchase Requests(UPRs)</span></h3>
    <div class="twelve columns">

        {{-- <div class="table-scroll"> --}}
            <table id="datatable-responsive" class="table ">
            <thead>
                <tr>
                    <th>UPR No.</th>
                    <th>Ref No.</th>
                    <th>Project</th>
                    <th>ABC</th>
                    <th>TYPE</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Date Prepared</th>
                    {{-- <th>State</th> --}}
                    {{-- <th>Calendar Days</th> --}}
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        {{-- </div> --}}
    </div>
</div>

@stop

@section('scripts')
<script type="text/javascript">

    table = $('#datatable-responsive').DataTable({
        "bLengthChange": false,
        processing: true,
        serverSide: true,
        "order": [[ 6, "desc" ]],
        ajax: {
                url: "{{route('datatables.biddings.unit-purchase-request')}}",
                // data: function (d) {
                    // d.search.value = $('#search-table').val();
                // }
            },

        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(3)').attr('style', 'text-align:right!important');
        },
        columns: [
            {data: 'public_upr_number', name: 'upr_number'},
            {data: 'ref_number', name: 'ref_number'},
            {data: 'project_name', name: 'project_name'},
            {data: 'total_amount', name: 'total_amount'},
            {data: 'type', name: 'type'},
            {data: 'status', name: 'status'},
            {data: 'created_at', name: 'created_at'},
            {data: 'date_prepared', name: 'date_prepared'},
            // {data: 'state', name: 'state'},
            // {data: 'calendar_days', name: 'calendar_days'},
        ],
        "fnInitComplete": function (oSettings, json) {
            $(".previous").html('<i class="nc-icon-outline arrows-1_tail-left"></i>');
            $(".next").html('<i class="nc-icon-outline arrows-1_tail-right"></i>');
        },
        "drawCallback": function(oSettings, json) {
            $(".previous").html('<i class="nc-icon-outline arrows-1_tail-left"></i>');
            $(".next").html('<i class="nc-icon-outline arrows-1_tail-right"></i>');
        }

    });

    // overide datatable filter for custom css
    $('#newForm').keyup(function(){
          table.search($(this).val()).draw() ;
    })
</script>
@stop