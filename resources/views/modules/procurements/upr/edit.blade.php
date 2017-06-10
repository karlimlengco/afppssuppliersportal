@section('modal')
    @include('modules.partials.modals.delete')
@stop

@section('contents')


<div class="row">
    <div class="six columns align-left">
        <h3>Roles</h3>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        {!! Form::model($role, ['route' => ['settings.roles.update', $role->id], 'id' => 'mainForm', 'method' => 'PUT']) !!}

            {!! Form::textField('name', 'Name') !!}

            {!! Form::textField('slug', 'Slug') !!}

            {!! Form::tagField('permissions', '', $permissions, ($role->permissions != "") ? array_keys($role->permissions) : "", ['data-max-items' => 10]) !!}

            <div class="row">

                <div class="six columns">
                    <button class="button"> <a href="{{route('settings.roles.index')}}"> Back </a></button>
                    <button class="button">Save</button>
                </div>

                <div class="six columns align-right">
                    <button class="button topbar__utility__button--modal" >Delete</button>
                </div>

            </div>
        {!! Form::close() !!}
    </div>
</div>


@stop
