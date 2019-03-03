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

    <div class="twelve columns">
        <a href="{{route($backRoute)}}" class="button button--pull-left" tooltip="Back">Go Back</a>
    </div>
</div>

<div class="row">
    <br>
    <h3><span style="border-bottom:2px solid black">List of Cancelled Unit Purchase Requests(UPRs)</span></h3>
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
                    {{-- <th>State</th> --}}
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
                url: "{{route('datatables.procurements.unit-purchase-request.completed')}}",
                // data: function (d) {
                    // d.search.value = $('#search-table').val();
                // }
            },
        columns: [
            {data: 'upr_number', name: 'upr_number'},
            {data: 'ref_number', name: 'ref_number'},
            {data: 'project_name', name: 'project_name'},
            {data: 'total_amount', name: 'total_amount'},
            {data: 'type', name: 'type'},
            {data: 'status', name: 'status'},
            {data: 'created_at', name: 'created_at'},
            // {data: 'state', name: 'state'},
        ],
        "fnInitComplete": function (oSettings, json) {
            $("#datatable-responsive_previous").html('<i class="nc-icon-outline arrows-1_tail-left"></i>');
            $("#datatable-responsive_next").html('<i class="nc-icon-outline arrows-1_tail-right"></i>');
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