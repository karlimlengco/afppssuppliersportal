@section('title')
PhilGeps Posting
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
    <div class="six columns align-left">
        <h3> </h3>
    </div>
    <div class="six columns utility utility--align-right" >
        {{-- <a class="button" href="{{route($createRoute)}}" tooltip="Add"><i class="nc-icon-mini ui-1_circle-add"></i></a> --}}
    </div>
</div>

<div class="row">
    <div class="twelve columns">

        <table id="datatable-responsive" class="table" >

            <thead>
                <tr>
                    <th>PhilGeps No.</th>
                    <th>RFQ No.</th>
                    <th>UPR No.</th>
                    <th>PhilGeps Posting</th>
                    <th>Deadline</th>
                    <th>Opening Time</th>
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
        "order": [[ 3, "desc" ]],
        ajax: {
                url: "{{route('datatables.procurements.philgeps-posting')}}",
                // data: function (d) {
                    // d.search.value = $('#search-table').val();
                // }
            },
        columns: [
            {data: 'philgeps_number', name: 'philgeps_number'},
            {data: 'rfq_number', name: 'rfq_number'},
            {data: 'upr_number', name: 'upr_number'},
            {data: 'philgeps_posting', name: 'philgeps_posting'},
            {data: 'deadline_rfq', name: 'deadline_rfq'},
            {data: 'opening_time', name: 'opening_time'},
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