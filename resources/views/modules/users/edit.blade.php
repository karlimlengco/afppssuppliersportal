@section('title')
Users
@stop

@section('contents')

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            {!! Form::model($user, $modelConfig['update']) !!}
            <div class="box-header with-border">
                {{-- <h3 class="box-title">Update User</h3> --}}
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-xs-4">
                        {!! Form::textField('first_name', 'First Name') !!}
                    </div>
                    <div class="col-xs-4">
                        {!! Form::textField('middle_name', 'Middle Name') !!}
                    </div>
                    <div class="col-xs-4">
                        {!! Form::textField('surname', 'Surname') !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        {!! Form::textField('email', 'Email Address') !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        {!! Form::textField('username', 'Username') !!}
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <a href="{{route('settings.users.index')}}" class="btn btn-default">Go Back</a>
                <button type="submit" class="btn btn-primary pull-right">Update</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop