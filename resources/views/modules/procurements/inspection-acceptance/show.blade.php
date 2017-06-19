@section('modal')
    @include('modules.partials.modals.notice_of_award')
@stop

@section('contents')
<div class="row">
    <div class="six columns align-left">
        <h3>Inspection And Acceptance Report</h3>
    </div>
    <div class="six columns align-right">
        @if(!$data->accepted_date)
            <a class="button" href="{{route($acceptRoute,$data->id)}}">Accept</a>
        @else
            <a class="button" href="{{route($indexRoute)}}">Print</a>
        @endif
        <a class="button" href="{{route($indexRoute)}}">Back</a>
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
</script>
@stop