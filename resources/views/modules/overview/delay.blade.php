@section('title')
Overview of Delayed Projects
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

<table class="table table--with-border ">
    <thead>
        <tr>
           <th class="align-center" width="15%">PC/CO</th>
           <th class="align-center" width="40%" >Project Name/UPR Number</th>
           <th class="align-center" width="10%">UPR Receipt</th>
           {{-- <th class="align-center" width="10%">Ref Number</th> --}}
           <th class="align-center" width="5%">ABC</th>
           <th class="align-center" width="5%">Approved Bid Price</th>
           <th class="align-center" width="10%">Current Status</th>
        </tr>
    </thead>
    <tbody>
        <?php $totalAbc = 0; ?>
        <?php $totalBid = 0; ?>
        <?php $totalResidual =  0; ?>
        @foreach($result as $data)
            <tr>
                <td>{{$data->name}}</td>
                <td>
                   <a target="_blank" href="/procurements/unit-purchase-requests/timelines/{{$data->id}}">{{$data->project_name}}</a>
                    <p style="margin-bottom:5px">
                    <small>{{$data->upr_number}}</small>
                    </p>
                </td>
                <td>{{$data->date_processed->format('d F Y')}}</td>
                {{-- <td>{{$data->ref_number}}</td> --}}
                {{-- <td style="text-transform: uppercase;">{{$data->status}}</td> --}}

                <td>{{formatPrice($data->total_abc)}}</td>
                <td>{{formatPrice($data->total_bid)}}</td>
                <td style="text-transform: uppercase;">
                  {{$data->status}}
                </td>
            </tr>
            <?php $totalAbc =  $totalAbc + $data->total_abc; ?>
            <?php $totalBid =  $totalBid + $data->total_bid; ?>
            <?php $totalResidual =  $totalResidual + $data->total_residual; ?>
        @endforeach
        <tr>
          <td>Total</td>
          <td></td>
          <td></td>
          <td>{{formatPrice($totalAbc)}}</td>
          <td>{{formatPrice($totalBid)}}</td>
          <td></td>
        </tr>
    </tbody>
</table>

@stop

@section('scripts')

@stop