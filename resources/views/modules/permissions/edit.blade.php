@section('modal')
    @include('modules.partials.modals.delete')
@stop

@section('contents')


<div class="row">
    <div class="six columns align-left">
        <h3>Permissions</h3>
    </div>
</div>

<div class="row">
    <div class="twelve column">

        {!! Form::model($permission, ['route' => ['settings.permissions.update', $permission->id], 'id' => 'mainForm', 'method' => 'PUT']) !!}

        {!! Form::textField('permission', '') !!}

        {!! Form::textField('description', '') !!}

        <div class="row">
            <div class="six columns">
                <button class="button"> <a href="{{route('settings.permissions.index')}}"> Back </a></button>
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
