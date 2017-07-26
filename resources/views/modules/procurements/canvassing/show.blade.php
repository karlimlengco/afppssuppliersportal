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
    @include('modules.partials.modals.signatories')
@stop

@section('contents')

<div class="message-box message-box--large message-box--success" role="alert">
    <span class="message-box__icon"><i class="nc-icon-outline ui-1_check-circle-08"></i></span>
    <span class="message-box__message">
    Add signatories first.
    <br>
    Click pen icon to edit proponents status, and click award icon to the corresponding proponent to prepare NOA.</span>
</div>

<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <button class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">


                <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" class=" button__options__item">Unit Purchase Request</a>

                <a href="{{route('procurements.blank-rfq.show', $data->rfq_id)}}" class=" button__options__item">Request For Quotation</a>
                @if(count($data->winners) != 0)
                <a href="{{route('procurements.noa.show', $data->winners->id)}}" class=" button__options__item">View NOA</a>
                @endif
            </div>
        </button>

        <a href="#" id="signatory-button" class="button" tooltip="Signatories"><i class="nc-icon-mini business_sign"></i> </a>
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back">
            <i class="nc-icon-mini arrows-1_tail-left"></i>
        </a>

        <a target="_blank" href="{{route('procurements.canvassing.print',$data->id)}}" class="button" tooltip="Print">
            <i class="nc-icon-mini tech_print"></i>
        </a>
        <a href="{{route('procurements.canvassing.logs', $data->id)}}" class="button" tooltip="Logs">
            <i class="nc-icon-mini files_archive-content"></i>
        </a>

        <a class="button" href="{{route($editRoute,$data->id)}}"><i class="nc-icon-mini design_pen-01"></i></a>
    </div>
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

            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Canvass Date :</strong> {{$data->canvass_date}} </li>

            @if($data->canvass_time)
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Opening Date :</strong> {{$data->canvass_date}}  {{$data->canvass_time}}</li>
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
                    <td>{{formatPrice($proponent->bid_amount)}}</td>
                    <td>{{formatPrice($data->upr->total_amount - $proponent->bid_amount)}}</td>
                    <td>{{$proponent->status}} </td>
                    <td>{{ $proponent->note }}</td>
                    <td>
                        <a href="{{route('procurements.rfq-proponents.show',$proponent->id)}}" tooltip="edit"> <span class="nc-icon-glyph design_pen-01"></span> </a>
                        @if($proponent->bid_amount != null)
                            @if($data->adjourned_time == null)
                            <a href="#" class="award-button award" data-id="{{$proponent->id}}" data-name="{{$proponent->supplier->name}}" tooltip="Award"> <span class="nc-icon-glyph business_award-48"></span> </a>
                            @endif
                            @if($data->winners != null && $data->winners->proponent_id == $proponent->id )
                                <a href="#" class=" award" tooltip="Winner"> <span class="nc-icon-glyph business_award-48"></span> </a>
                            @endif
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

$('#signatory-button').click(function(e){
    e.preventDefault();
    $('#signatory-modal').addClass('is-visible');
})

$('.award-button').click(function(e){
    e.preventDefault();
    $('#award-modal').addClass('is-visible');
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