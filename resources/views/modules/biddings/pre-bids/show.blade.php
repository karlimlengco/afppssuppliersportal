@section('title')
Pre-Bid Conference
@stop

@section('styles')
@stop

@section('modal')
@stop

@section('contents')
<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
    </div>
</div>

<div class="data-panel">
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">UPR No. :</strong> {{$data->upr_number}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Ref No. :</strong> {{$data->ref_number}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Transaction Date :</strong> {{$data->transaction_date}} </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul class="data-panel__list">
            @if($data->is_scb_issue)
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Approved Date :</strong> Yes </li>
            @endif
            @if($data->resched_date)
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Re-Sched Date :</strong> {{$data->resched_date}} </li>
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
@stop