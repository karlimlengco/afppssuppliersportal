@section('title')
Canvassing
@stop

@section('modal')
    @include('modules.partials.modals.notice_of_award')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        <button class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                @if($data->status == 'pending')
                    <a href="#" class="topbar__utility__button--modal button__options__item">Process</a>
                @endif

                <a href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" class=" button__options__item">Unit Purchase Request</a>

                <a href="{{route('procurements.blank-rfq.show', $data->rfq_id)}}" class=" button__options__item">Request For Quotation</a>

                <a href="{{route('procurements.noa.show', $data->id)}}" class=" button__options__item">Awardee</a>
            </div>
        </button>

        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back">
            <i class="nc-icon-mini arrows-1_tail-left"></i>
        </a>

        <a class="button" href="{{route($editRoute,$data->id)}}"><i class="nc-icon-mini design_pen-01"></i></a>
    </div>
</div>

<div class="row">
    <div class="six columns pull-left">
        <ul>
            <li> <strong>UPR No. :</strong> {{$data->upr_number}} </li>
            <li> <strong>RFQ No. :</strong> {{$data->rfq_number}} </li>
            <li> <strong>Canvass Date :</strong> {{$data->canvass_date}} </li>
            @if($data->adjourned_time)
            <li> <strong>Adjourned Time :</strong> {{$data->adjourned_time}} </li>
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
                        <a href="#" class="topbar__utility__button--modal award" data-id="{{$proponent->id}}" data-name="{{$proponent->supplier->name}}" tooltip="Award"> <span class="nc-icon-glyph business_award-48"></span> </a>
                        @endif
                        @if($proponent->is_awarded)
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
</script>
@stop