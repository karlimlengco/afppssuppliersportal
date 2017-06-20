@section('modal')
    @include('modules.partials.modals.notice_of_award')
@stop

@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3>Vouchers</h3>
    </div>
    <div class="six columns align-right">
        @if($data->status == 'pending')
        <button class="button topbar__utility__button--modal">PROCESS</button>
        @endif

        <a class="button" href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="UPR"> <span class="nc-icon-glyph business_agenda"></span> </a>
        <a href="{{route('procurements.blank-rfq.show', $data->rfq_id)}}" class="button" tooltip="RFQ"> <span class=" nc-icon-glyph ui-1_edit-74"></span> </a>
        <a class="button" href="{{route($indexRoute)}}">BACK</a>
        <a class="button" href="{{route($editRoute,$data->id)}}">EDIT</a>
    </div>
</div>

<div class="row">
    <div class="six columns pull-left">
        <ul>
            <li> <strong>UPR No. :</strong> {{$data->upr_number}} </li>
            <li> <strong>RFQ No. :</strong> {{$data->rfq_number}} </li>
            <li> <strong>Transaction DAte :</strong> {{$data->transaction_date}} </li>
            <li> <strong>BIR Address. :</strong> {{$data->bir_address}} </li>
            <li> <strong>Final Tax :</strong> {{$data->final_tax}} </li>
            <li> <strong>Expanded Witholding Tax :</strong> {{$data->expanded_witholding_tax}} </li>
            <li> <strong>Prepared By:</strong> {{($data->users) ? $data->users->first_name .' '.$data->users->surname : " "}} </li>
        </ul>
    </div>
</div>
@stop

@section('scripts')
@stop