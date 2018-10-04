@section('title')
Notice To Proceed
@stop

@section('search')
{!! Form::open(['route'  => 'procurements.ntp.index', 'method'=>'get', 'style' =>'width:100%']) !!}
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
</div>

<div class="row">
    <div class="twelve columns">


        <table id="datatable-responsive" class="table" >

            <thead>
                <tr>
                    <th>RFQ No.</th>
                    <th>PO Date</th>
                    <th>Bid Amount</th>
                    <th>Status</th>
                    {{-- <th>Supplier</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach($resources as $data)
                  <tr>
                    <td> <a href="{{route( 'procurements.ntp.show',[$data->id] )}}">  {{$data->upr_number}} </a></td>
                    <td>{{$data->purchase_date}}</td>
                    <td>{{formatPrice($data->bid_amount)}}</td>
                    <td>{{$data->status}}</td>
                    {{-- <td>{{$data->name}}</td> --}}
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
    //     "order": [[ 1, "desc" ]],
    //     ajax: {
    //             url: "{{route('datatables.procurements.ntp')}}",
    //         },
    //     columns: [
    //         {data: 'rfq_number', name: 'rfq_number'},
    //         {data: 'purchase_date', name: 'purchase_date'},
    //         {data: 'bid_amount', name: 'bid_amount'},
    //         {data: 'status', name: 'status'},
    //         {data: 'name', name: 'name'},
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