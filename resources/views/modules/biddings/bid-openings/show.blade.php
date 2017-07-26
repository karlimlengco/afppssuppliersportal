@section('title')
Bid Opening
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

        <?php $count = count($data->upr->bid_proponents); ?>
        @foreach($data->upr->bid_proponents as $proponent)
            @if($proponent->bid_amount != null)
                <?php $count --; ?>
            @endif
        @endforeach
        @if($count == 0)
            @if(!$data->closing_date)
                <a href="{{route('biddings.bid-openings.closed',$data->id)}}" class="button" tooltip="Submit"><i class="nc-icon-mini ui-2_disk"></i></a>
            @endif
        @endif
    </div>
</div>

<div class="data-panel">
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">UPR No. :</strong> {{$data->upr_number}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Ref No. :</strong> {{$data->ref_number}} </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Transaction Date :</strong> {{$data->transaction_date}} </li>
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Processed By :</strong> {{$data->user->first_name .' '. $data->user->surname}} </li>
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

<div class="row">
    <div class="twelve columns">
        <h3>Proponents</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Bid Amount</th>
                    <th>LCB</th>
                    <th>SCB</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->upr->bid_proponents as $proponent)
                <tr>
                    <td>{{$proponent->proponent_name}}</td>
                    <td>{{$proponent->bid_amount}}</td>
                    <td>{{($proponent->is_lcb == 1) ? "yes" : "no"}}</td>
                    <td>{{($proponent->is_scb == 1) ? "yes" : "no"}}</td>
                    <td><a href="{{route('biddings.proponents.show',[$data->id,$proponent->id])}}"> <span class="nc-icon-mini design_pen-01"></span></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
@stop

@section('scripts')
@stop