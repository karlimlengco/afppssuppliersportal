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
    <div class="four columns">
        {!! Form::textField('first_name', 'First Name') !!}
    </div>
    <div class="four columns">
        {!! Form::textField('middle_name', 'Middle Name') !!}
    </div>
    <div class="four columns">
        {!! Form::textField('surname', 'Last Name') !!}
    </div>
</div>

<div class="row">
    <div class="four columns">
        {!! Form::textField('username', 'Username') !!}
    </div>
    <div class="four columns">
        {!! Form::passwordField('password', 'Password') !!}
    </div>
    <div class="four columns">
        {!! Form::passwordField('password_confirmation', 'Password Confirmation') !!}
    </div>
</div>

<div class="row">
    <div class="four columns">
        {!! Form::selectField('unit_id', 'Unit', $unit_lists) !!}
    </div>
    <div class="four columns">
        {!! Form::textField('designation', 'Designation') !!}
    </div>
    <div class="four columns">
        {!! Form::textField('email', 'Email') !!}
    </div>
</div>


<div class="row">
    <div class="six columns">
        {!! Form::textField('contact_number', 'Contact Number') !!}
    </div>
    <div class="six columns">
        {!! Form::selectField('gender', 'Gender', $genders) !!}
    </div>
</div>

<row class="">
    <div class="twelve columns">
        {!! Form::textareaField('address', 'Address', null, ['rows'=>4]) !!}
    </div>
</row>

{!! Form::close() !!}

@stop
