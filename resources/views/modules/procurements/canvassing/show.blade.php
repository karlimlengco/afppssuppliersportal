@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3>Unit Purchase Request</h3>
    </div>
    <div class="six columns align-right">
        @if($data->status == 'pending')
        <button class="button topbar__utility__button--modal">Process</button>
        @endif
        <a class="button" href="{{route($indexRoute)}}">Back</a>
        <a class="button" href="{{route($editRoute,$data->id)}}">Edit</a>
    </div>
</div>

<div class="row">
    <div class="six columns pull-left">
        <ul>
            <li> <strong>UPR No. :</strong> {{$data->upr_number}} </li>
            <li> <strong>RFQ No. :</strong> {{$data->rfq_number}} </li>
            <li> <strong>Canvass Date :</strong> {{$data->canvass_date}} </li>
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
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @foreach($proponent_list as $proponent)
                <tr>
                    <td>{{($proponent->supplier) ? $proponent->supplier->name :""}}</td>
                    <td>{{$proponent->date_processed}}</td>
                    <td>{{($proponent->users) ? $proponent->users->first_name ." ". $proponent->users->surname :""}} </td>
                    <td>
                        <a href="{{route('procurements.rfq-proponents.show',$proponent->id)}}" tooltip="attachments"> <span class="nc-icon-glyph ui-1_attach-87"></span> </a>
                        <a href="{{route('procurements.rfq-proponents.show',$proponent->id)}}" tooltip="Award"> <span class="nc-icon-glyph business_award-48"></span> </a>
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

</script>
@stop