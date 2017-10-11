@section('title')
Proponent
@stop


@section('breadcrumbs')

    @if(isset($breadcrumbs))
      @foreach($breadcrumbs as $route => $crumb)
        @if($crumb->hasLink())
        <a href="{{ $crumb->link() }}" class="topbar__breadcrumbs__item">{{ $crumb->title() }}</a>
        @else
        <a href="#" class="topbar__breadcrumbs__item">{{ $crumb->title() }}</a>
        @endif
      @endforeach
    @else
    <li><a href="#">Application</a></li>
    @endif

@stop

@section('modal')
    @include('modules.partials.modals.dropzone')
@stop

@section('contents')

{!! Form::model($data, $modelConfig['update']) !!}
<div class="row">
    <div class="twelve columns align-right utility utility--align-right">
        @if($data->rfq->canvassing)
        <a href="{{route('procurements.canvassing.show',$data->rfq->canvassing->id)}}" class="button button--pull-left" tooltip="Back">
            <i class="nc-icon-mini arrows-1_tail-left"></i>
        </a>
        @endif
        <a class="button topbar__utility__button--modal" href="#"><i class="nc-icon-mini ui-1_attach-86"></i></a>
        <button class="button" tooltip="Save" type="submit"><i class="nc-icon-mini ui-2_disk"></i></button>
    </div>
</div>

<div class="row">
    <div class="six columns">
        {!! Form::textField('bid_amount', 'Bid Amount')!!}
    </div>
    <div class="six columns">
        {!! Form::selectField('status', 'Status', ['passed' => 'Passed', 'failed' => 'Failed'], ($status->name == '(failed)') ? 'failed' : 'passed' )!!}
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        {!! Form::textareaField('remarks', 'Remarks', null, ['rows'=>3]) !!}
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
        <ul class="data-panel__list">
            <li class="data-panel__list__item"> <strong class="data-panel__list__item__label">Phone # :</strong> {{$data->supplier->phone_1}} </li>
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
{!! Form::close() !!}

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