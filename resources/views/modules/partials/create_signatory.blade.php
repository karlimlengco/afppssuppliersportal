<div class="modal" id="create-signatory-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="create-signatory-form" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">New Signatory</h1>
            </div>

            <div class="row">
                <div class="four columns">
                    {!! Form::textField('name', 'Name', null, ['required']) !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('ranks', 'Rank', null, ['required']) !!}
                </div>
                <div class="four columns">
                    {!! Form::textField('branch', 'Branch', null, ['required']) !!}
                </div>
            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::textField('designation', 'Designation', null, ['required']) !!}
                </div>
            </div>

            <div class="modal__dialogue__body">

                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="_method" type="hidden" value="POST">
            </div>

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>