@section('title')
Users
@stop

@section('contents')

<div class="row">
    <div class="six columns align-left">
        <h3></h3>
    </div>
</div>
<div class="row">
    <div class="twelve columns">
        {!! Form::open(['route'=>'settings.users.store',  'class' => 'form-horizontal form-label-left' ]) !!}

            {!! Form::textField('first_name', 'First Name') !!}
            {!! Form::textField('middle_name', 'Middle Name') !!}
            {!! Form::textField('surname', 'Last Name') !!}
            {!! Form::textField('username', 'Username')!!}
            {!! Form::textField('email', 'Email')!!}
            {!! Form::textField('contact_number', 'Contact Number') !!}
            {!! Form::selectField('gender', 'Gender', $genders) !!}
            {!! Form::textareaField('address', 'Address', null, ['rows'=>4]) !!}

            <a class="button" href="{{route('settings.users.index')}}">Back</a>
            <button type="submit" class="button">Save</button>
        {!! Form::close() !!}
    </div>
</div>


@stop