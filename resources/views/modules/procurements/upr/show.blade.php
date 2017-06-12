@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3>Unit Purchase Request</h3>
    </div>
    <div class="six columns align-right">
        <button class="button">
            <a href="{{route($indexRoute)}}">Back</a>
        </button>
        <button class="button">
            <a href="{{route($editRoute,$data->id)}}">Edit</a>
        </button>
    </div>
</div>

<div class="row">
    <div class="six columns pull-left">
        <ul>
            <li> <strong>UPR No. :</strong> {{$data->upr_number}} </li>
            <li> <strong>AFPPS No. :</strong> {{$data->afpps_ref_number}} </li>
            <li> <strong>Date Prepared :</strong> {{$data->date_prepared}} </li>
            <li> <strong>Place of delivery :</strong> {{($data->centers) ? $data->centers->name :""}} </li>
            <li> <strong>Mode of Procurement :</strong> {{($data->modes) ? $data->modes->name :""}} </li>
            <li> <strong>Units :</strong> {{($data->unit) ? $data->unit->name :""}} </li>
        </ul>
    </div>
    <div class="six columns pull-right">
        <ul>
            <li> <strong>Total ABC :</strong> {{number_format($data->total_amount,2)}} </li>
            <li> <strong>Chargeability :</strong> {{($data->charges) ? $data->charges->name :""}} </li>
            <li> <strong>Account Code :</strong> {{($data->accounts) ? $data->accounts->new_account_code :""}} </li>
            <li> <strong>Fund Validity :</strong> {{$data->fund_validity}} </li>
            <li> <strong>Terms of Payment :</strong> {{($data->terms) ? $data->terms->name :""}} </li>
            <li> <strong>Prepared by :</strong> {{($data->users) ? $data->users->first_name ." ". $data->users->surname :""}} </li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        <ul>
            <li> <strong>Purpose of Purchase :</strong>
                <ul>
                    <li> {{$data->purpose}} </li>
                </ul>
            </li>
            <li> <strong>Other Essential Info :</strong>
                <ul>
                    <li> {{$data->other_infos}} </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="twelve columns">
        <h3>Items</h3>
        <table class='table' id="item_table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->items as $item)
                    <tr>
                        <td>{{$item->item_description}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->unit_measurement}}</td>
                        <td>{{$item->unit_price}}</td>
                        <td>{{$item->total_amount}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('scripts')
@stop