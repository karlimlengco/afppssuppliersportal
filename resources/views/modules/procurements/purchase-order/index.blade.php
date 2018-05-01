@section('title')
Purchase Order
@stop

@section('search')
{!! Form::open(['route'  => 'procurements.purchase-orders.index', 'method'=>'get', 'style' =>'width:100%']) !!}
<input type="text"  name="search" class="sidebar__search__input" id="newForm"  placeholder="Looking for something?">
<button style="float:right" class="sidebar__search__button"><i class="nc-icon-mini ui-1_zoom"></i></button>
{!! Form::close() !!}
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
                    <th>RFQ No.</th>
                    <th>UPR No.</th>
                    <th>PO Date</th>
                    <th>Bid Amount</th>
                    <th>MFO Released</th>
                    <th>PC/CO Released</th>
                </tr>
            </thead>
            <tbody>
              @foreach($resources as $data)
                <tr>
                  <td> <a href="{{route( 'procurements.purchase-orders.show',[$data->id] )}}">  {{$data->rfq_number}} </a></td>
                  <td>{{$data->upr_number}}</td>
                  <td>{{$data->purchase_date}}</td>
                  <td>{{$data->bid_amount}}</td>
                  <td>{{$data->mfo_released_date}}</td>
                  <td>{{$data->funding_released_date}}</td>
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

    // table = $('#datatable-responsive').DataTable({
    //     "bLengthChange": false,
    //     processing: true,
    //     serverSide: true,
    //     "order": [[ 2, "desc" ]],
    //     ajax: {
    //             url: "{{route('datatables.procurements.purchase-orders')}}",
    //             // data: function (d) {
    //                 // d.search.value = $('#search-table').val();
    //             // }
    //         },
    //     columns: [
    //         {data: 'rfq_number', name: 'rfq_number'},
    //         {data: 'upr_number', name: 'upr_number'},
    //         {data: 'purchase_date', name: 'purchase_date'},
    //         {data: 'bid_amount', name: 'bid_amount'},
    //         {data: 'mfo_released_date', name: 'mfo_released_date'},
    //         {data: 'funding_released_date', name: 'funding_released_date'},
    //     ],
    //     "fnInitComplete": function (oSettings, json) {
    //         $("#datatable-responsive_previous").html('<i class="nc-icon-outline arrows-1_tail-left"></i>');
    //         $("#datatable-responsive_next").html('<i class="nc-icon-outline arrows-1_tail-right"></i>');
    //     },
    //     "drawCallback": function(oSettings, json) {
    //         $(".previous").html('<i class="nc-icon-outline arrows-1_tail-left"></i>');
    //         $(".next").html('<i class="nc-icon-outline arrows-1_tail-right"></i>');
    //     }

    // });

    // // overide datatable filter for custom css
    // $('#newForm').keyup(function(){
    //       table.search($(this).val()).draw() ;
    // })
</script>
@stop