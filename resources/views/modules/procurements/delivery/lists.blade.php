@section('title')
Notice Of Delivery
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
</div>

<delivery-order></delivery-order>

<div class="row">
    <div class="twelve columns">

        <table id="datatable-responsive" class="table" >

            <thead>
                <tr>
                    <th>UPR Number</th>
                    <th>DR Number</th>
                    <th>Expected Delivery Date.</th>
                    <th>Status</th>
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
        ajax: {
                url: "{{route('datatables.procurements.delivery-orders-lists', $id)}}",
            },
        columns: [
            {data: 'upr_number', name: 'upr_number'},
            {data: 'delivery_number', name: 'delivery_number'},
            {data: 'delivery_date', name: 'delivery_date'},
            {data: 'status', name: 'status'},
        ],
        "fnInitComplete": function (oSettings, json) {
            $("#datatable-responsive_previous").html('<i class="nc-icon-outline arrows-1_tail-left"></i>');
            $("#datatable-responsive_next").html('<i class="nc-icon-outline arrows-1_tail-right"></i>');
            console.log("{{$id}}")
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