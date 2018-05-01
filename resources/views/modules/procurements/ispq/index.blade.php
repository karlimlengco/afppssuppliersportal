@section('title')
Invitation to Submit Price Quotation
@stop

@section('search')
{!! Form::open(['route'  => 'procurements.ispq.index', 'method'=>'get', 'style' =>'width:100%']) !!}
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
        <h3></h3>
    </div>
    <div class="six columns utility utility--align-right" >
        <a class="button" href="{{route($createRoute)}}" tooltip="Add"><i class="nc-icon-mini ui-1_circle-add"></i></a>
    </div>
</div>

<div class="row">
    <div class="twelve columns">

        <table id="datatable-responsive" class="table" >

            <thead>
                <tr>
                    <th>Transaction Date</th>
                    <th>Venue</th>
                    <th> </th>
                </tr>
            </thead>
            <tbody>
                @foreach($resources as $data)
                  <tr>
                    <td> <a href="{{route( 'procurements.ispq.edit',[$data->id] )}}">  {{$data->transaction_date}} </a></td>
                    <td>{{$data->venue}}</td>
                    <td><a target="_blank" href="{{route( 'procurements.ispq.print',[$data->id] )}}" tooltip="Print">  <span class="nc-icon-glyph tech_print"></span> </a>
                    </td>
                  </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop

@section('scripts')
<script type="text/javascript">

    // table = $('#datatable-responsive').DataTable({
    //     "bLengthChange": false,
    //     "order": [[ 0, "desc" ]],
    //     processing: true,
    //     serverSide: true,
    //     ajax: {
    //             url: "{{route('datatables.procurements.ispq')}}",
    //             // data: function (d) {
    //                 // d.search.value = $('#search-table').val();
    //             // }
    //         },
    //     columns: [
    //         {data: 'transaction_date', name: 'transaction_date'},
    //         {data: 'venue', name: 'venue'},
    //         {data: 'print_button', name: 'print_button'},
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