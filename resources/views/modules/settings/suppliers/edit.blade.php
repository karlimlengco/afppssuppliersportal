@section('title')
Suppliers
@stop

@section('modal')
    @include('modules.partials.modals.delete')
@stop

@section('contents')


<div class="row">
    <div class="six columns align-left">
        <h3> </h3>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        {!! Form::model($data, $modelConfig['update']) !!}

            <div class="row">
                <div class="six columns">
                    {!! Form::textField('name', 'Name') !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('owner', 'Owner') !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::textField('address', 'Address') !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('tin', 'Tin') !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::selectField('bank_id', 'Bank', $bank_lists) !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('branch', 'Branch') !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::textField('account_number', 'Account Number') !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('account_type', 'Account Type') !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::textField('cell_1', 'Cell #') !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('email_1', 'Email') !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::textField('phone_1', 'Phone #') !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('fax_1', 'Fax') !!}
                </div>
            </div>
            <div class="row">
                <div class="six columns">
                    {!! Form::textField('cell_2', 'Secondary Cell #') !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('email_2', 'Secondary Email') !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::textField('phone_2', 'Secondary Phone #') !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('fax_2', 'Secondary Fax') !!}
                </div>
            </div>


            <div class="row">

                <div class="six columns">
                    <a class="button" href="{{route($indexRoute)}}"> Back </a>
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
