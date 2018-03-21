@section('title')
Request For Quotation
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
        {{-- <a class="button" href="{{route($createRoute)}}" tooltip="Add"><i class="nc-icon-mini ui-1_circle-add"></i></a> --}}
        {!! Form::open(['route'  => 'procurements.blank-rfq.index', 'method'=>'get']) !!}
    <div class="five columns " >
          <input type="text" name="search" class="input">
    </div>
    <div class="one columns " >
          <button class="button">Search</button>
    </div>
        {!! Form::close() !!}
</div>
        <br>
        <br>
        <br>

<div class="row">
    <div class="twelve columns">

        <table  class="table" >

            <thead>
                <tr>
                    <th>RFQ No.</th>
                    <th>UPR No.</th>
                    <th>Deadline</th>
                    <th>Opening Time</th>
                    <th>Transaction Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resources as $data)
                  <tr>
                    <td>{{$data->rfq_number}}</td>
                    <td>{{$data->upr_number}}</td>
                    <td>{{$data->deadline}}</td>
                    <td>{{$data->opening_time}}</td>
                    <td>{{$data->transaction_date}}</td>
                    <td>{{$data->status}}</td>
                  </tr>
                @endforeach
            </tbody>
        </table>

        <?php echo $resources->render(); ?>
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
                url: "{{route('datatables.procurements.blank-rfq')}}",
                // data: function (d) {
                    // d.search.value = $('#search-table').val();
                // }
            },
        columns: [
            {data: 'rfq_number', name: 'rfq_number'},
            {data: 'upr_number', name: 'upr_number'},
            {data: 'deadline', name: 'deadline'},
            {data: 'opening_time', name: 'opening_time'},
            {data: 'transaction_date', name: 'transaction_date'},
            {data: 'status', name: 'status'}
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