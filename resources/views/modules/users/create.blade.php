@section('title')
Users
@stop

@section('contents')

{!! Form::open(['route'=>'settings.users.store']) !!}

<div class="row">
    <div class="twelve columns align-left utility utility--align-right">
        <a href="{{route('settings.users.index')}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button type="submit" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

    </div>
</div>

<div class="row">
    <div class="twelve columns">

            {!! Form::textField('first_name', 'First Name') !!}
            {!! Form::textField('middle_name', 'Middle Name') !!}
            {!! Form::textField('surname', 'Last Name') !!}
            {!! Form::textField('username', 'Username')!!}
            {!! Form::textField('email', 'Email')!!}
            {!! Form::textField('contact_number', 'Contact Number') !!}
            {!! Form::selectField('gender', 'Gender', $genders) !!}
            {!! Form::textareaField('address', 'Address', null, ['rows'=>4]) !!}

    </div>
</div>

{!! Form::close() !!}

@stop