@section('title')
Post Qualification
@stop

@section('styles')
@stop

@section('modal')
    @include('modules.partials.bid-modals.failed-post-qual')
    @include('modules.partials.modals.notice_of_award')
@stop

@section('contents')
<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

        <a href="#" class="button" id="fail-pq-button" tooltip="Failed"><i class="nc-icon-mini ui-1_bold-remove"></i></a>
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
                @if($proponent->is_lcb == 1 && $proponent->is_scb == 1)
                <tr>
                    <td>{{$proponent->proponent_name}}</td>
                    <td>{{$proponent->bid_amount}}</td>
                    <td>{{($proponent->is_lcb == 1) ? "yes" : "no"}}</td>
                    <td>{{($proponent->is_scb == 1) ? "yes" : "no"}}</td>
                    <td><a href="#" class="award-button award"  data-id="{{$proponent->id}}" data-name="{{$proponent->proponent_name}}">winner</a></td>
                </tr>
                @endif
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