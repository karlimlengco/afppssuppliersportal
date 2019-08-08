@section('title')
Users
@stop

@section('contents')

{!! Form::open(['route'=>'settings.user-suppliers.store', 'v-form-check']) !!}

<div class="row">
    <div class="twelve columns align-left utility utility--align-right">
        <a href="{{route('settings.user-suppliers.index')}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button type="submit" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

    </div>
</div>

<div class="row">
    <div class="four columns">
        {!! Form::textField('first_name', 'Company') !!}
    </div>
    <div class="four columns">
        {!! Form::textField('surname', 'Owner') !!}
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
    <div class="six columns">
        {!! Form::textField('email', 'Email') !!}
    </div>
    <div class="six columns">
        {!! Form::textField('contact_number', 'Contact Number') !!}
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        {!! Form::textareaField('address', 'Address', null, ['rows'=>4]) !!}
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        {!! Form::tagField('suppliers', 'Supplier', $suppliers ) !!}
    </div>
</div>

<input type="hidden" name="gender" value="male">
<input type="hidden" name="unit_id" value="x">
<input type="hidden" name="designation" value="x">
{!! Form::close() !!}

@stop
