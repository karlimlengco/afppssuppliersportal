@section('title')
Permissions
@stop

@section('modal')
    @include('modules.partials.modals.delete')
@stop

@section('contents')


{!! Form::model($role, ['route' => ['settings.permissions.update', $role->id], 'id' => 'mainForm', 'method' => 'PUT']) !!}

<div class="row">
    <div class="twelve columns align-left utility utility--align-right">
        <a href="{{route('settings.permissions.index')}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>

        <button type="submit" class="button topbar__utility__button--modal"  tooltip="Save">
        <i class="nc-icon-mini ui-2_disk"></i>
        </button>

        <a href="" class="button topbar__utility__button--modal" tooltip="Delete"><i class="nc-icon-mini ui-1_trash"></i></a>
    </div>
</div>

<div class="row">
    <div class="twelve column">

        {!! Form::textField('permission', '') !!}

        {!! Form::textField('description', '') !!}

    </div>
</div>

{!! Form::close() !!}
@stop
