@section('title')
Permissions
@stop

@section('contents')

{!! Form::open(['route' => 'settings.permissions.store']) !!}
<div class="row">
    <div class="twelve columns align-left utility utility--align-right">
        <a href="{{route('settings.permissions.index')}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button type="submit" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

    </div>
</div>

<div class="row">
    <div class="twelve columns">
        {!! Form::textField('permission', 'Permission') !!}
        {!! Form::textField('description', 'Description') !!}
    </div>
</div>
{!! Form::close() !!}
@stop
