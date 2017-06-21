@section('title')
Vouchers
@stop

@section('modal')
    @include('modules.partials.modals.delete')
@stop

@section('contents')


<div class="row">
    <div class="six columns align-left">
        <h3></h3>
    </div>
</div>

<div class="row">
    <div class="twelve columns">
        {!! Form::model($data, $modelConfig['update']) !!}

            <div class="row">
                <div class="six columns">
                    {!! Form::selectField('rfq_id', 'RFQ Number', $rfq_list) !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('transaction_date', 'Transaction Date') !!}
                </div>
            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::textareaField('bir_address', 'BIR Address', null, ['rows'=>3]) !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::numberField('final_tax', 'Final') !!}
                </div>
                <div class="six columns">
                    {!! Form::numberField('expanded_witholding_tax', 'Expanded Witholding Tax') !!}
                </div>
            </div>


            <div class="row">
                <div class="six columns">
                    <button type="reset" class="button"> <a href="{{route($indexRoute,$data->id)}}">Back</a> </button>
                    <button type="submit" class="button">Save</button>
                </div>

                <div class="six columns align-right">
                    <button class="button topbar__utility__button--modal" >Delete</button>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>


@stop

@section('scripts')
