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

<div class="data-panel">
    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Name :</strong> {{$data->supplier->name}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Cellphone # :</strong> {{$data->supplier->cell_1}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Phone # :</strong> {{$data->supplier->phone_1}} </li>
        </ul>
    </div>

    <div class="data-panel__section">
        <ul class="data-panel__list">hone # :</strong> {{$data->supplier->phone_1}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Fax # :</strong> {{$data->supplier->fax_1}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Address :</strong> {{$data->supplier->address}} </li>
        </ul>
    </div>

    <div class="data-panel__section">
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Processed Date :</strong> {{$data->date_processed}} </li>
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Prepared By :</strong> {{($data->users) ? $data->users->first_name ." ". $data->users->surname :""}}  </li>
        </ul>
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