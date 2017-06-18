@section('modal')
    @include('modules.partials.modals.proponents')
@stop

@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3>Request For Quotation</h3>
    </div>
    <div class="six columns align-right">
        <a class="button topbar__utility__button--modal" href="#">Add Proponents</a>
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
            <li> <strong>ABC :</strong> {{ formatPrice($data->upr->total_amount) }} </li>
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
    </div>
</div>

@if(count($data->proponents) != 0)
<div class="row">
    <div class="twelve columns">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Processed Date</th>
                    <th>Bid Amount</th>
                    <th>Prepared By</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->proponents as $proponent)
                <tr>
                    <td>{{($proponent->supplier) ? $proponent->supplier->name :""}}</td>
                    <td>{{$proponent->date_processed}}</td>
                    <td>{{formatPrice($proponent->bid_amount)}}</td>
                    <td>{{($proponent->users) ? $proponent->users->first_name ." ". $proponent->users->surname :""}} </td>
                    <td>
                        <a href="{{route('procurements.rfq-proponents.show',$proponent->id)}}" tooltip="attachments"> <span class="nc-icon-glyph ui-1_attach-87"></span> </a>
                        <a href="#" tooltip="remove"> <span class="nc-icon-glyph ui-1_trash-simple"></span> </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@stop

@section('scripts')
<script type="text/javascript">
    // datepicker
    // pickmeup('#id-field-date_processed', {
    //     format  : 'Y-m-d'
    // });
    var picker = new Pikaday(
    {
        field: document.getElementById('id-field-date_processed'),
        firstDay: 1,
        // minDate: new Date(),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
    });


</script>
@stop