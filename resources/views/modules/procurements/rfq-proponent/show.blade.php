@section('title')
Proponent
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
        <a class="button" href="{{route($indexRoute,$data->rfq_id)}}">Back</a>
    </div>
</div>

<div class="row">
    <div class="six columns pull-left">
        <ul>
            <li> <strong>Name :</strong> {{$data->supplier->name}} </li>
            <li> <strong>Processed Date :</strong> {{$data->date_processed}} </li>
            <li> <strong>Prepared By :</strong> {{($data->users) ? $data->users->first_name ." ". $data->users->surname :""}}  </li>
        </ul>
    </div>
    <div class="six columns pull-right">
    </div>
</div>

@if(count($data->attachments) != 0)
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
                    <td> <a href="{{route('procurements.rfq-proponents.attachments.download', $attachment->id)}}"> {{$attachment->name}} </a></td>
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
<script src="/js/dropzone.js"></script>
<script type="text/javascript">


</script>
@stop