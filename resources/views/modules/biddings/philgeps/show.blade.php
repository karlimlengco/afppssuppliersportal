@section('title')
PhilGeps Posting
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
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Ref No. :</strong> {{$data->upr->ref_number}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Transaction Date:</strong> {{$data->transaction_date}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Status :</strong> {{($data->status == 1) ? "Approved" : "Needs Re-Post"}} </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Philgeps Number :</strong> {{$data->philgeps_number}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Philgeps Posting :</strong> {{$data->philgeps_posting}} </li>
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