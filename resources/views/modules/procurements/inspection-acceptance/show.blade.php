@section('title')
Inspection And Acceptance Report
@stop

@section('modal')
    @include('modules.partials.modals.notice_of_award')
    @include('modules.partials.modals.iar-signatories')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns utility utility--align-right" >
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>


        <button class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                <a id="signatory-button" href="#" class="button__options__item" > Signatories</a>

            </div>
        </button>

        @if(!$data->accepted_date)
            <a class="button" tooltip="Accept"  href="{{route($acceptRoute,$data->id)}}"><i class="nc-icon-mini ui-1_check-bold"></i></a>
        @else
        @endif
        <a class="button" href="{{route($printRoute, $data->id)}}"><i class="nc-icon-mini tech_print"></i></a>
    </div>
</div>
<hr>

<div class="row">
    <div class="six columns pull-left">
        <h3>Inspection Details</h3>
        <ul>
            <li> <strong>UPR No. :</strong> {{$data->upr_number}} </li>
            <li> <strong>RFQ No. :</strong> {{$data->rfq_number}} </li>
            <li> <strong>Delivery No. :</strong> {{$data->delivery_number}} </li>
            <li> <strong>Status :</strong> {{$data->status}} </li>
            <li> <strong>Inspection Date :</strong> {{$data->inspection_date}} </li>
            <li> <strong>Nature Of Delivery :</strong> {{$data->nature_of_delivery}} </li>
            <li> <strong>Prepared By :</strong> {{($data->users) ? $data->users->first_name .' '.$data->users->surname : ""}} </li>
            @if($data->accepted_date)
                <li> <strong>Date Accepted :</strong> {{$data->accepted_date}} </li>
            @endif

            @foreach($data->invoices as $invoice)
                <li>
                    <strong>Invoice #. :</strong> {{$invoice->invoice_number}}
                    <ul>
                        <li>{{$invoice->invoice_date}}</li>
                    </ul>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="six columns pull-right">
        <h3>Supplier Details</h3>
        <ul>
            <li> <strong>Name :</strong> {{$supplier->name}} </li>
            <li> <strong>Owner :</strong> {{$supplier->owner}} </li>
            <li> <strong>Address :</strong> {{$supplier->address}} </li>
            <li> <strong>TIN :</strong> {{$supplier->tin}} </li>
            <li> <strong>Cellphone # :</strong> {{$supplier->cell_1}} </li>
            <li> <strong>Phone # :</strong> {{$supplier->phone_1}} </li>
            <li> <strong>FAX :</strong> {{$supplier->fax_1}} </li>
            <li> <strong>Email :</strong> {{$supplier->email_1}} </li>
        </ul>

    </div>
</div>


<div class="row">
    <div class="twelve columns">
        <ul>
            <li> <strong>Findings :</strong> {{$data->findings}} </li>
            <li> <strong>Recommendation :</strong> {{$data->recommendation}}   </li>
        </ul>
    </div>
</div>

@stop

@section('scripts')
<script type="text/javascript">

$('#signatory-button').click(function(e){
    e.preventDefault();
    $('#signatory-modal').addClass('is-visible');
})
</script>
@stop