@section('title')
Canvassing
@stop

@section('modal')
    @include('modules.partials.modals.notice_of_award')
    @include('modules.partials.modals.signatories')
@stop

@section('contents')
<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <button class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">

                <a href="#"  class=" button__options__item" id="signatory-button">Add Signatories</a>

                <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" class=" button__options__item">Unit Purchase Request</a>

                <a href="{{route('procurements.blank-rfq.show', $data->rfq_id)}}" class=" button__options__item">Request For Quotation</a>
            </div>
        </button>

        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back">
            <i class="nc-icon-mini arrows-1_tail-left"></i>
        </a>

        <a target="_blank" href="{{route('procurements.canvassing.print',$data->id)}}" class="button" tooltip="Print">
            <i class="nc-icon-mini tech_print"></i>
        </a>

        {{-- <a class="button" href="{{route($editRoute,$data->id)}}"><i class="nc-icon-mini design_pen-01"></i></a> --}}
    </div>
</div>

<div class="data-panel">
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">UPR No. :</strong> {{$data->upr_number}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">RFQ No. :</strong> {{$data->rfq_number}} </li>
        </ul>
    </div>
    <div class="data-panel__section">
        <ul class="data-panel__list">

            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Canvass Date :</strong> {{$data->canvass_date}} </li>

            @if($data->canvass_time)
                <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Opening Date :</strong> {{$data->created_at}} </li>
            @endif
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
                    <th>Prepared By</th>
                    <th>Bid Amount</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @foreach($proponent_list as $proponent)
                <tr>
                    <td>{{($proponent->supplier) ? $proponent->supplier->name :""}}</td>
                    <td>{{$proponent->date_processed}}</td>
                    <td>{{($proponent->users) ? $proponent->users->first_name ." ". $proponent->users->surname :""}} </td>
                    <td>{{formatPrice($proponent->bid_amount)}}</td>
                    <td>
                        <a href="{{route('procurements.rfq-proponents.show',$proponent->id)}}" tooltip="attachments"> <span class="nc-icon-glyph ui-1_attach-87"></span> </a>
                        @if($data->adjourned_time == null)
                        <a href="#" class="award-button award" data-id="{{$proponent->id}}" data-name="{{$proponent->supplier->name}}" tooltip="Award"> <span class="nc-icon-glyph business_award-48"></span> </a>
                        @endif
                        @if($proponent->winners != null)
                            <a href="#" class=" award" tooltip="Winner"> <span class="nc-icon-glyph business_award-48"></span> </a>
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
</script>
@stop