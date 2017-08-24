@section('title')
Pre-Bid Conference
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
    @include('modules.partials.bid-modals.failed-prebid')
@stop

@section('modal')
@stop

@section('contents')
<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        @if($data->failed_remarks == null)
        <a href="#" class="button" id="fail-pq-button" tooltip="Failed"><i class="nc-icon-mini ui-1_bold-remove"></i></a>
        @endif


        <button type="button" class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                <a class="button__options__item" href="{{route('procurements.unit-purchase-requests.timelines', $data->upr_id)}}">View Timelines</a>
            </div>
        </button>

        <a href="{{route('biddings.pre-bids.logs', $data->id)}}" class="button" tooltip="Logs">
            <i class="nc-icon-mini files_archive-content"></i>
        </a>

        <a href="{{route('biddings.pre-bids.edit',$data->id)}}" class="button" tooltip="Edit">
            <i class="nc-icon-mini design_pen-01"></i>
        </a>
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
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Ref No. :</strong> {{$data->ref_number}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Transaction Date :</strong> @if($data->transaction_date) {{CreateCarbon('Y-m-d', $data->transaction_date)->format('d F Y')}}@endif</li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul class="data-panel__list">
            @if($data->is_scb_issue)
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Approved Date :</strong> Yes </li>
            @endif
            @if($data->resched_date)
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Re-Sched Date :</strong> @if($data->resched_date) {{CreateCarbon('Y-m-d', $data->resched_date)->format('d F Y')}}@endif</li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Re-Sched Remarks :</strong> {{$data->resched_remarks}} </li>
            @endif
        </ul>
    </div>
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Processed By:</strong> {{$data->user->first_name ." ". $data->user->surname}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Days To Complete :</strong> {{$data->days}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Remarks :</strong> {{$data->remarks or "&nbsp;"}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Action :</strong> {{$data->action or "&nbsp;"}} </li>
        </ul>
    </div>
</div>

@stop

@section('scripts')
<script type="text/javascript">

    $('#fail-pq-button').click(function(e){
        e.preventDefault();
        $('#fail-pq-modal').addClass('is-visible');
    })
</script>
@stop