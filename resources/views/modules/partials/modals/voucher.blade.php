<div class="modal"  id="voucher-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="delete-form" action="{{route('procurements.vouchers.store')}}" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Create Voucher</h1>
            </div>

            <div class="modal__dialogue__body">
                <div class="row">
                    <div class="four columns">
                        {!! Form::dateField('voucher_transaction_date', 'Transaction Date') !!}
                    </div>
                    <div class="four columns">
                        {!! Form::numberField('final_tax', 'Final Tax') !!}
                    </div>
                    <div class="four columns">
                        {!! Form::numberField('expanded_witholding_tax', 'Expanded Witholding Tax') !!}
                    </div>
                </div>
                <div class="row">
                    <div class="four columns">
                        {!! Form::textField('amount', 'Amount') !!}
                    </div>
                    <div class="four columns">
                        {!! Form::textField('final_tax_amount', 'Final Tax Amount') !!}
                    </div>
                    <div class="four columns">
                        {!! Form::textField('ewt_amount', 'EWT Amount') !!}
                    </div>
                </div>

                <div class="row">
                    <div class="twelve columns">
                        {!! Form::textareaField('bir_address', 'BIR Address', null, ['rows'=>3]) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="twelve columns">
                        {!! Form::textareaField('remarks', 'Remarks', null, ['rows'=>3]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="twelve columns">
                        {!! Form::textareaField('action', 'Action', null, ['rows'=>3]) !!}
                    </div>
                </div>
                {{-- @if($data->rfq) --}}
                <input name="rfq_id" type="hidden" value="{{ $data->id }}">
                {{-- @endif --}}
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="_method" type="hidden" value="POST">
            </div>

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>