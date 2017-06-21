@section('title')
Permissions
@stop

@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3> </h3>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        {!! Form::open(['route' => 'settings.permissions.store', 'id' => 'mainForm',  'class' => 'form-horizontal form-label-left' ]) !!}

                {!! Form::textField('permission', 'Permission') !!}
                {!! Form::textField('description', 'Description') !!}

        <div class="row">

            <div class="six columns">
                  <a class="button" href="{{route('settings.permissions.index')}}"> Back </a>
                <button class="button">Save</button>
            </div>

        </div>
        {!! Form::close() !!}

    </div>
</div>
@stop
