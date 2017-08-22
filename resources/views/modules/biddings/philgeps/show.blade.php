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

@section('styles')
@stop

@section('modal')
@stop

@section('contents')
<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <a href="{{route('biddings.philgeps.logs', $data->id)}}" class="button" tooltip="Logs">
            <i class="nc-icon-mini files_archive-content"></i>
        </a>

        <a class="button" href="{{route('biddings.philgeps.edit',$data->id)}}">
            <i class="nc-icon-mini design_pen-01"></i></a>
    </div>

    <hr>
    <br>
    <div class="twelve columns align-right utility utility--align-right">
            Go to UPR
            <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}"  class="button button--pull-right"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
    </div>
</div>

<div class="data-panel">
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">UPR No. :</strong> {{$data->upr_number}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Ref No. :</strong> {{$data->upr->ref_number}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Transaction Date:</strong>@if($data->transaction_date) {{CreateCarbon('Y-m-d', $data->transaction_date)->format('d F Y')}}@endif </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Status :</strong> {{($data->status == 1) ? "Approved" : "Needs Re-Post"}} </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Philgeps Number :</strong> {{$data->philgeps_number}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Philgeps Posting :</strong> @if($data->philgeps_posting) {{CreateCarbon('Y-m-d', $data->philgeps_posting)->format('d F Y')}}@endif</li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Days to Complete :</strong> {{$data->days}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Remarks :</strong> {{$data->remarks or "&nbsp;"}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Action :</strong> {{$data->action or "&nbsp;"}} </li>
        </ul>
    </div>
</div>

@stop

@section('scripts')
@stop