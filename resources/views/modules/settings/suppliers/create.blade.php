@section('title')
Suppliers
@stop

@section('contents')

{!! Form::open($modelConfig['store']) !!}

<div class="row">
    <div class="twelve columns align-left utility utility--align-right">
        <a href="{{route($indexRoute)}}" class="button button--pull-left" tooltip="Back"><i class="nc-icon-mini arrows-1_tail-left"></i></a>
        <button type="submit" class="button" tooltip="Save"><i class="nc-icon-mini ui-2_disk"></i></button>

    </div>
</div>

<div class="row">
    <div class="twelve columns">

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

    </div>
</div>

{!!Form::close()!!}
@stop
