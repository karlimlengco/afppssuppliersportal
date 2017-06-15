@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3>Request For Quotation</h3>
    </div>
    <div class="six columns align-right">
            <a class="button" href="{{route($indexRoute)}}">Back</a>
            <a class="button" href="{{route($editRoute,$data->id)}}">Edit</a>
    </div>
</div>

<div class="row">
    <div class="six columns pull-left">
        <ul>
            <li> <strong>RFQ No. :</strong> {{$data->rfq_number}} </li>
            <li> <strong>UPR No. :</strong> {{$data->upr_number}} </li>
            <li> <strong>Status :</strong> {{ ucfirst($data->status) }} </li>
        </ul>
    </div>
    <div class="six columns pull-right">
        <ul>
            <li> <strong>Deadline to Submit :</strong> {{ $data->deadline }} </li>
            <li> <strong>Canvas Opening Time :</strong> {{ $data->opening_time }} </li>
            <li> <strong>TransactionDate :</strong> {{ $data->transaction_date }} </li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="six columns">
        <h3>Proponents</h3>
    </div>
    <div class="six columns">
        <a href="#"></a>
    </div>
</div>

@stop

@section('scripts')
@stop