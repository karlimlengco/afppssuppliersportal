@section('title')
Roles
@stop

@section('contents')
{!! Form::open(['route' => 'settings.roles.store', 'id' => 'mainForm']) !!}

<div class="row">
    <div class="twelve columns align-left utility utility--align-right">
        <a href="{{route('settings.roles.index')}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button type="submit" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

    </div>
</div>

<div class="row">
    <div class="twelve columns">
            {!! Form::textField('name', 'Name') !!}

            {!! Form::textField('slug', 'Slug') !!}

            {!! Form::tagField('permissions', 'Permissions', $permissions) !!}
    </div>
</div>

{!!Form::close()!!}
@stop
