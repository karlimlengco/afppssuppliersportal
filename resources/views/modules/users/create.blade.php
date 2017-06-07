@section('contents')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Users  <small>Create</small></h2>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                {!! Form::open(['route'=>'settings.users.store',  'class' => 'form-horizontal form-label-left' ]) !!}

                        {!! Form::textField('first_name', 'First Name') !!}
                        {!! Form::textField('middle_name', 'Middle Name') !!}
                        {!! Form::textField('surname', 'Last Name') !!}
                        {!! Form::textField('username', 'Username')!!}
                        {!! Form::textField('email', 'Email')!!}
                        {!! Form::textField('contact_number', 'Contact Number') !!}
                        {!! Form::selectField('gender', 'Gender', $genders) !!}
                        {!! Form::textareaField('address', 'Address', null, ['rows'=>4]) !!}

                    <div class="ln_solid"></div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <a href="{{route('settings.users.index')}}" class="btn btn-primary">Cancel</a>
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>


@stop