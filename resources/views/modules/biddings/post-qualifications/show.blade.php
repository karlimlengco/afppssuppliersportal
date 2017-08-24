@section('title')
Post Qualification
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
    @include('modules.partials.bid-modals.failed-post-qual')
    @include('modules.partials.modals.notice_of_award')
@stop

@section('contents')

<div class="message-box message-box--large message-box--success" role="alert">
    <span class="message-box__icon"><i class="nc-icon-outline ui-1_check-circle-08"></i></span>
    <span class="message-box__message">
    <br>
    Click winner to mark proponent as winner.</span>
</div>


<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

        <a href="#" class="button" id="fail-pq-button" tooltip="Failed"><i class="nc-icon-mini ui-1_bold-remove"></i></a>


        <button type="button" class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                <a class="button__options__item" href="{{route('procurements.unit-purchase-requests.timelines', $data->upr_id)}}">View Timelines</a>
            </div>
        </button>


        <a href="{{route('biddings.post-qualifications.logs', $data->id)}}" class="button" tooltip="Logs">
            <i class="nc-icon-mini files_archive-content"></i>
        </a>

        <a href="{{route('biddings.post-qualifications.edit',$data->id)}}" class="button" tooltip="Edit">
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
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">ABC :</strong>Php {{formatPrice($data->upr->total_amount)}} </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li  class="data-panel__list__item"> <strong  class="data-panel__list__item__label">Transaction Date :</strong> @if($data->transaction_date) {{CreateCarbon('Y-m-d', $data->transaction_date)->format('d F Y')}}@endif </li>
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
                    <th>Residual</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->upr->bid_proponents as $proponent)
                <tr>
                    <td>{{$proponent->proponent_name}}</td>
                    <td>@if($proponent->bid_amount != null) {{formatPrice($proponent->bid_amount)}} @endif</td>
                    <td>{{ formatPrice($data->upr->total_amount - $proponent->bid_amount) }}</td>
                    <td style="text-transform: capitalize">{{$proponent->status}}</td>
                    <td><a href="#" class="award-button award"  data-id="{{$proponent->id}}" data-name="{{$proponent->proponent_name}}">winner</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
@stop

@section('scripts')<script type="text/javascript">

    $('#fail-pq-button').click(function(e){
        e.preventDefault();
        $('#fail-pq-modal').addClass('is-visible');
    })


    $('.award-button').click(function(e){
        e.preventDefault();
        $('#award-modal').addClass('is-visible');
    })

    $(document).on('click', '.award', function(e){
        var name = $(this).data('name');
        var id  = $(this).data('id');
        var pq_id  = "{{$data->id}}";
        $("#proponent").html('');
        $("#proponent").html(name);
        var form = document.getElementById('award-form').action;
        document.getElementById('award-form').action = "/biddings/award-to/"+pq_id+"/"+id;
    });

    var awarded_date = new Pikaday(
    {
        field: document.getElementById('id-field-awarded_date'),
        firstDay: 1,
        defaultDate: new Date(),
        setDefaultDate: new Date(),
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });
</script>
@stop