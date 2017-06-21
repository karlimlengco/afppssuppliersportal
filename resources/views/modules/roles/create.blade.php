@section('title')
Roles
@stop

@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3></h3>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        {!! Form::open(['route' => 'settings.roles.store', 'id' => 'mainForm']) !!}
            {!! Form::textField('name', 'Name') !!}

            {!! Form::textField('slug', 'Slug') !!}

            {!! Form::tagField('permissions', 'Permissions', $permissions) !!}

            <button type="reset" class="button"> <a href="{{route('settings.roles.index')}}">Back</a> </button>
            <button type="submit" class="button">Save</button>

        {!!Form::close()!!}
    </div>
</div>

@stop
