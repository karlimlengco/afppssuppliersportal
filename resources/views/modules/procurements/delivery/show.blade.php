@section('title')
Notice Of Delivery
@stop

@section('contents')
{!! Form::model($data, $modelConfig['update']) !!}
<div class="row">
    <div class="six columns align-left">
        <h3></h3>
    </div>
    <div class="six columns align-right">

        <a class="button" href="{{route('procurements.unit-purchase-requests.show', $data->upr_id)}}" tooltip="UPR"> <span class="nc-icon-glyph business_agenda"></span> </a>
        <a href="{{route('procurements.blank-rfq.show', $data->rfq_id)}}" class="button" tooltip="RFQ"> <span class=" nc-icon-glyph ui-1_edit-74"></span> </a>
        <a href="{{route('procurements.purchase-orders.show', $data->po_id)}}" class="button" tooltip="PURCHASE ORDER"> <span class=" nc-icon-glyph shopping_cart"></span></a>
        @if(!$data->delivery_date)
        <button type="submit" class="button">Save</button>
        @else
            @if(!$data->date_completed)
                <a class="button" href="{{route($completeRoute,$data->id)}}">Completed</a>
            @endif
        @endif
        <a class="button" href="{{route($indexRoute)}}">Back</a>
    </div>
</div>

<div class="row">
    <div class="six columns pull-left">
        <ul>
            <li> <strong>RFQ Number :</strong> {{$data->rfq_number}} </li>
            <li> <strong>UPR Number :</strong> {{$data->upr_number}} </li>
            <li> <strong>Expected Delivery Date :</strong> {{$data->expected_date}} </li>
            <li> <strong>Created By :</strong> {{($data->creator) ? $data->creator->first_name .' '. $data->creator->surname : " "}} </li>
        </ul>
    </div>
    <div class="six columns pull-right">
        <ul>

            @if(!$data->delivery_date)
                <li>  {!! Form::textField('delivery_date', 'Delivery Date') !!} </li>
                <li>   {!! Form::textField('delivery_number', 'Delivery Number') !!} </li>
                <li>   {!! Form::textareaField('notes', 'Notes') !!} </li>
            @else
                <li> <strong>Delivery Date :</strong> {{$data->delivery_date}} </li>
                <li> <strong>Delivery Number :</strong> {{$data->delivery_number}} </li>
                <li> <strong>Delivery Note :</strong> {{$data->notes}} </li>
                <li> <strong>Status :</strong> {{ucwords($data->status)}} </li>
                @if($data->status)
                    <li> <strong>Completed Date :</strong> {{$data->date_completed}} </li>
                @endif
            @endif
        </ul>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        <table class='table' id="item_table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Received Qty</th>
                    <th>Unit</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>

                @if(!$data->delivery_date)
                    @foreach($data->items as $item)
                        <input type="hidden" name="ids[]" value="{{$item->id}}">
                        <tr>
                            <td>{{$item->description}}</td>
                            <td>{{$item->quantity}}</td>
                            <td> {{Form::text('received_quantity[]', $item->received_quantity, ['class' => 'input'])}} </td>
                            <td>{{$item->unit}}</td>
                            <td>{{formatPrice($item->price_unit)}}</td>
                            <td>{{formatPrice($item->total_amount)}}</td>
                        </tr>
                    @endforeach
                @else
                    @foreach($data->items as $item)
                        <tr>
                            <td>{{$item->description}}</td>
                            <td>{{$item->quantity}}</td>
                            <td>{{$item->received_quantity}}</td>
                            <td>{{$item->unit}}</td>
                            <td>{{formatPrice($item->price_unit)}}</td>
                            <td>{{formatPrice($item->total_amount)}}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
{!!Form::close()!!}
@stop

@section('scripts')
<script type="text/javascript">

var delivery_date = new Pikaday(
{
    field: document.getElementById('id-field-delivery_date'),
    firstDay: 1,
    // minDate: new Date(),
    maxDate: new Date(2020, 12, 31),
    yearRange: [2000,2020]
});

</script>
@stop