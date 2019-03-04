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
    <a href="#" class="topbar__breadcrumbs__item">Supplier UPR</a>
    @endif

@stop

@section('contents')

<div class="row">

    <div class="twelve columns">
        <a href="{{route('settings.suppliers.show', $id)}}" class="button button--pull-left" tooltip="Back">Go Back</a>
    </div>
</div>

<div class="row">
    <br>
    <h3><span style="border-bottom:2px solid black">List of Unit Purchase Requests(UPRs)</span></h3>
    <div class="twelve columns">
        <table id="datatable-responsive" class="table ">
            <thead>
                <tr>
                    <th>UPR No.</th>
                    <th>Project Name/ Activity</th>
                    <th>ABC</th>
                    <th>Fund</th>
                    <th>Mode</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Date Prepared</th>
                </tr>
            </thead>
            <tbody>
  
              @foreach($resources as $data)
                <tr>
                  <td> <a traget="_blank" href="{{route( 'procurements.unit-purchase-requests.show',[$data->id] )}}">  {{$data->upr_number}} </a></td>
                  <td>{{$data->project_name}}</td>
                  <td>{{formatPrice($data->total_amount)}}</td>
                  <td>{{$data->chargeability}}</td>
                  <td>{{$data->type}}</td>
                  <td>{{$data->status}}</td>
                  <td>{{$data->created_at->format('d-m-Y')}}</td>
                  <td>{{$data->date_processed->format('d-m-Y')}}</td>
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

</script>
@stop