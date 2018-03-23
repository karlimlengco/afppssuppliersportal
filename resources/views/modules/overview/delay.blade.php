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
    <?php $totalAbc = 0; ?>
    <?php $totalBid = 0; ?>
    <?php $totalResidual =  0; ?>

    @foreach($result as $data)
        <?php $totalAbc =  $totalAbc + $data->total_abc; ?>
        <?php $totalBid =  $totalBid + $data->total_bid; ?>
        <?php $totalResidual =  $totalResidual + $data->total_residual; ?>
    @endforeach
    <thead>
        <tr>
           <th   style="background: #222222"  class="align-center" width="10%">PC/CO/BAC</th>
           <th   style="background: #222222"  class="align-center" width="30%" >Project Name/UPR Number</th>
           <th   style="background: #222222"  class="align-center" width="10%">UPR Receipt</th>
           {{-- <th   style="background: #222222"  class="align-center" width="10%">Ref Number</th> --}}
           <th   style="background: #222222"  class="align-center" width="5%">ABC  <br><small  style="color:white">(in Php)</small></th>
           <th   style="background: #222222"  class="align-center" width="5%">Approved Contract Price  <br><small  style="color:white">(in Php)</small></th>
           <th   style="background: #222222; text-align:center!important"  class="align-center" width="10%">Current Status</th>
           <th   style="background: #222222; text-align:center!important"  class="align-center" width="10%">Next Stage</th>
           <th   style="background: #222222; text-align:center!important"  class="align-center" width="10%">Expected Finish Date</th>
           <th   style="background: #222222; text-align:center!important"  class="align-center" width="10%"># Days delayed</th>
           <th   style="background: #222222; text-align:center!important"  class="align-center" width="10%">Justification</th>
        </tr>
    </thead>
    <tbody>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td style='text-align:right'><strong>{{formatPrice($totalAbc)}}</strong></td>
          <td style='text-align:right'><strong>{{formatPrice($totalBid)}}</strong></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        @foreach($result as $data)
        <?php
            $today          =   \Carbon\Carbon::now()->format('Y-m-d');
            $today          =   createCarbon('Y-m-d', $today);

            $next_date      =   $data->date_processed;
            if($data->next_due)
            {
                $next_date      =   createCarbon('Y-m-d', $data->next_due);
            }
            $d =  $today->diffInDaysFiltered(function (\Carbon\Carbon $date)  {return $date->isWeekday();}, createCarbon('Y-m-d H:i:s',$next_date) );
          ?>
            <tr>
                <td>{{$data->name}}</td>
                <td style="font-size:18px;">
                   <a target="_blank" href="/procurements/unit-purchase-requests/timelines/{{$data->id}}">{{$data->project_name}}</a>
                    <p style="margin-bottom:5px">
                    <small style='font-size:12px'>({{$data->upr_number}})</small>
                    </p>
                </td>
                <td>{{$data->date_processed->format('d M Y')}}</td>
                {{-- <td>{{$data->ref_number}}</td> --}}
                {{-- <td style="text-transform: uppercase;">{{$data->status}}</td> --}}

                <td style="text-align:right">{{formatPrice($data->total_abc)}}</td>
                <td style="text-align:right">{{formatPrice($data->total_bid)}}</td>
                <td style="text-transform: uppercase;text-align:left!important">
                  {{$data->status}}
                </td>
                <td style="text-transform: uppercase;text-align:left!important">
                  {{$data->next_step}}
                </td>
                <td style="text-transform: uppercase;text-align:left!important">
                  {{\Carbon\Carbon::createFromFormat('Y-m-d',$data->next_due)->format('d M Y')}}
                </td>
                <td style="text-transform: uppercase;text-align:center!important">
                  {{$d}}
                </td>
                <td style="text-transform: uppercase;text-align:left!important">
                  {{$data->last_remarks}}
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
          <td style='text-align:right'><strong>{{formatPrice($totalAbc)}}</strong></td>
          <td style='text-align:right'><strong>{{formatPrice($totalBid)}}</strong></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
    </tbody>
</table>

@stop

@section('scripts')

@stop