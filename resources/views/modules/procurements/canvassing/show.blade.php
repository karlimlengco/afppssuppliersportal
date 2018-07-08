@section('title')
Canvassing
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

@section('modal')
    @include('modules.partials.modals.notice_of_award')
    @include('modules.partials.modals.canvass_signatories')
    @include('modules.partials.modals.failed-canvass')
@stop

@section('contents')

<div class="message-box message-box--large message-box--success" role="alert">
    <span class="message-box__icon"><i class="nc-icon-outline ui-1_check-circle-08"></i></span>
    <span class="message-box__message">
    Add signatories first.
    <br>
    Click pen icon to edit proponents status, and click award icon to the corresponding proponent to prepare NOA.
    <br>
    For attendance click pen icon.</span>
</div>

<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <span class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">


                <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" class=" button__options__item">Unit Purchase Request</a>
{{--
                @if(count($data->winners) == 0)
                    <a href="#" id="failed-button" class=" button__options__item">Failed Bid</a>
                @endif
 --}}
                <a target="_blank" href="{{route('procurements.canvassing.print',$data->id)}}" class=" button__options__item">Print Abstract</a>
                <a target="_blank" href="{{route('procurements.canvassing.cop',$data->id)}}" class=" button__options__item">Print COP</a>
                <a target="_blank" href="{{route('procurements.canvassing.rop',$data->id)}}" class=" button__options__item">Print ROP</a>
                <a target="_blank" href="{{route('procurements.canvassing.mom',$data->id)}}" class=" button__options__item">Print MOM</a>

                <a href="{{route('procurements.canvassing.logs', $data->id)}}" class="button__options__item" tooltip="Logs">
                    View Logs
                </a>
                <a class="button__options__item" href="{{route('procurements.unit-purchase-requests.timelines', $data->upr_id)}}">View Timelines</a>

            </div>
        </span>

        <a href="#" id="signatory-button" class="button" tooltip="Signatories"><i class="nc-icon-mini business_sign"></i> </a>
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back">
            <i class="nc-icon-mini arrows-1_tail-left"></i>
        </a>

        <a class="button" href="{{route($editRoute,$data->id)}}"><i class="nc-icon-mini design_pen-01"></i></a>
    </div>
    @if($data->adjourned_time != null)
        <hr>
        <br>
        <div class="twelve columns align-right utility utility--align-right">
            Go to UPR
            <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="UPR" class="button button--pull-right"><i class="nc-icon-mini arrows-1_bold-right"></i></a>
        </div>
    @endif
</div>

<div class="data-panel">
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">UPR No. :</strong> {{$data->upr_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">RFQ No. :</strong> {{$data->rfq_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">ABC :</strong> {{formatPrice($data->upr->total_amount)}} </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul class="data-panel__list">

            @if($data->canvass_time)
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Opening Date :</strong> {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->canvass_date." ".$data->canvass_time)->format('dHi M Y')}}</li>
            @endif
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Remarks :</strong> {{$data->remarks}} </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul class="data-panel__list">
            @if($data->opens != null)
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Opening By :</strong> {{$data->opens->first_name}} {{$data->opens->surname}} </li>
            @endif

            @if($data->adjourned_time)
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Adjourned Time :</strong> {{$data->adjourned_time}} </li>
            @endif
        </ul>
    </div>
</div>
<div class="row">
    <div class="twelve columns">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Processed Date</th>
                    <th>Bid Amount</th>
                    <th>Residual</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @foreach($proponent_list as $proponent)
                <tr>
                    <td>{{($proponent->supplier) ? $proponent->supplier->name :""}}</td>
                    <td>{{$proponent->date_processed}}</td>
                    <td>{{($proponent->bid_amount) ? formatPrice($proponent->bid_amount) : ""}}</td>
                    <td>{{formatPrice($data->upr->total_amount - $proponent->bid_amount)}}</td>
                    <td>{{$proponent->status}} </td>
                    <td>{{ $proponent->note }}</td>
                    <td>
                        @if($data->adjourned_time == null || \Sentinel::getUser()->hasRole('Admin'))
                          <a href="{{route('procurements.rfq-proponents.show',$proponent->id)}}" tooltip="edit"> <span class="nc-icon-glyph design_pen-01"></span> </a>
                        @endif
                        @if($proponent->bid_amount != null)
                            @if($data->adjourned_time == null)
                            <a href="#" class="award-button award" data-id="{{$proponent->id}}" data-name="{{$proponent->supplier->name}}" tooltip="Award"> mark as winner </a>
                            @endif
                        @endif
                        @if($data->upr && $data->upr->noa && $data->upr->noa->proponent_id == $proponent->id)
                          <span>Winner</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('scripts')
<script type="text/javascript">


    $(document).on("click", "#noa-submit",function(e) {
        setTimeout(function () { $("#noa-submit").prop('disabled',true); }, 0);
    });
$('#signatory-button').click(function(e){
    e.preventDefault();
    $('#signatory-modal').addClass('is-visible');
})

$('.award-button').click(function(e){
    e.preventDefault();
    $('#award-modal').addClass('is-visible');
})

$('#failed-button').click(function(e){
    e.preventDefault();
    $('#failed-modal').addClass('is-visible');
})

// click award
$(document).on('click', '.award', function(e){
    var name = $(this).data('name');
    var id  = $(this).data('id');
    var canvasid  = "{{$data->id}}";
    $("#proponent").html('');
    $("#proponent").html(name);
    var form = document.getElementById('award-form').action;
    document.getElementById('award-form').action = "/procurements/award-to/"+canvasid+"/"+id;
});

// overide selectize tag
$('#id-field-signatory_id').selectize({
    delimiter: ',',
    persist: false,
    maxItems: 3
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