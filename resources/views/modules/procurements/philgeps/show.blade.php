@section('title')
PhilGeps Posting
@stop

@section('modal')
    @include('modules.partials.modals.dropzone')
@stop

@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3></h3>
    </div>
    <div class="six columns align-right">
        <a class="button topbar__utility__button--modal" href="#">Add Attachment</a>
        <a class="button" href="{{route($editRoute,$data->id)}}">EDIT</a>
        <a class="button" href="{{route($indexRoute)}}">BACK</a>
    </div>
</div>
<br>
<br>
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