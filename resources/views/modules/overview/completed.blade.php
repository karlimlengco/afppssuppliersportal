@section('title')
Overview Completed
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
           <th class="align-center" width="40%" >UPR Number</th>
           {{-- <th class="align-center" width="20%">Ref Number</th> --}}
           <th class="align-center" width="5%">ABC</th>
           <th class="align-center" width="5%">Approved Contract</th>
           <th class="align-center" width="5%">Residual</th>
           <th class="align-center" width="10%">Date Prepared</th>
        </tr>
    </thead>
    <tbody>
        @foreach($result as $data)
            <tr>
                <td>{{$data->name}}</td>
                <td>
                    {{$data->upr_number}}
                    <p style="margin-bottom:5px"><a target="_blank" href="/procurements/unit-purchase-requests/timelines/{{$data->id}}"><small>{{$data->project_name}}</small></a></p>
                </td>
                {{-- <td>{{$data->ref_number}}</td> --}}
                <td>{{formatPrice($data->total_abc)}}</td>
                <td>{{formatPrice($data->total_bid)}}</td>
                <td>{{formatPrice($data->total_residual)}}</td>
                <td>{{$data->date_processed->format('d F Y')}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@stop

@section('scripts')

@stop