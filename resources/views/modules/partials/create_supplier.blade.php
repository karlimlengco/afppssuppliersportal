<div class="modal" id="create-supplier-modal" style="z-index:999999999">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="create-supplier-form" accept-charset="UTF-8">

            <div class="moda__dialogue__head">
                <h1 class="modal__title">New Supplier</h1>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::textField('name', 'Name', null, ['required']) !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('owner', 'Owner', null, ['required']) !!}
                </div>
            </div>

            <div class="row">
                <div class="twelve columns">
                    {!! Form::textField('address', 'Address', null, ['required']) !!}
                </div>
            </div>

            <div class="row">
                <div class="six columns">
                    {!! Form::textField('cell_1', 'Cell #', null, ['required']) !!}
                </div>
                <div class="six columns">
                    {!! Form::textField('email_1', 'Email Address', null, ['required']) !!}
                </div>
            </div>

            <div class="modal__dialogue__body">

                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="_method" type="hidden" value="POST">
            </div>

            <div class="modal__dialogue__foot">
                <button type="button" class="button pull-left" id='close-supplier-button'>Cancel</button>
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>