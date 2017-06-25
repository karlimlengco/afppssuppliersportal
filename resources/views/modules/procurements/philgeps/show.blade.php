@section('title')
PhilGeps Posting
@stop

@section('modal')
    @include('modules.partials.modals.dropzone')
@stop

@section('contents')

<div class="row">
    <div class="twelve columns align-right utility utility--align-right">

        <button class="button button--options-trigger" tooltip="Options">
            <i class="nc-icon-mini ui-2_menu-dots"></i>
            <div class="button__options">
                <a href="#" class=" topbar__utility__button--modal button__options__item">Add Attachment</a>
                <a href="{{route('procurements.unit-purchase-requests.show',$data->upr_id)}}" class=" button__options__item">Unit Purchase Request</a>
                <a href="{{route('procurements.blank-rfq.show',$data->rfq_id)}}" class=" button__options__item">Request For Quotation</a>
            </div>
        </button>
        <a class="button" href="{{route($editRoute,$data->id)}}">
            <i class="nc-icon-mini design_pen-01"></i></a>
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
    </div>
</div>

<div class="row">
    <div class="six columns pull-left">
        <ul>
            <li> <strong>RFQ Number :</strong> {{$data->rfq_number}} </li>
            <li> <strong>UPR Number :</strong> {{$data->upr_number}} </li>
            <li> <strong>PhilGeps Number :</strong> {{$data->philgeps_number}} </li>
            <li> <strong>Transaction Date :</strong> {{$data->transaction_date}} </li>
            <li> <strong>PhilGeps Posting Date :</strong> {{$data->philgeps_posting}} </li>
            <li> <strong>RFQ Submition Deadline :</strong> {{$data->deadline_rfq}} </li>
            <li> <strong>Canvas Opening Time :</strong> {{$data->opening_time}} </li>
        </ul>
    </div>
</div>
<br>
<br>
@if(count($data->attachments) != 0)
<h3>Attachments</h3>
<br>
<div class="row">
    <div class="twelve columns">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>File Name</th>
                    <th>Uploaded By</th>
                    <th>Upload Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->attachments as  $attachment)
                <tr>
                    <td> <a href="{{route('procurements.philgeps-posting.attachments.download', $attachment->id)}}"> {{$attachment->name}} </a></td>
                    <td>{{$attachment->file_name}}</td>
                    <td>{{($attachment->users) ? $attachment->users->first_name ." ". $attachment->users->surname :""}}</td>
                    <td>{{$attachment->upload_date}}</td>
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
</script>
@stop