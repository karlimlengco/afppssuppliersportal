<div class="modal" id="unit-attachment-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="my-awesome-dropzone" enctype="multipart/form-data"  action="{{ route($modelConfig['add_attachment']['route'][0], $modelConfig['add_attachment']['route'][1]) }}"   accept-charset="UTF-8" >
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Add Attachment</h1>
            </div>

            <div class="modal__dialogue__body">
                <div class="row">
                    <div class="six columns">
                        {!! Form::textField('name', 'Name') !!}
                    </div>
                    <div class="six columns">
                        {!! Form::selectField('type', 'Type', [
                            'dti'                   => 'DTI',
                            'mayors_permit'         => 'Mayors Permit',
                            'tax_clearance'         => 'Tax Clearance',
                            'philgeps_registraion'  => 'Philgeps Registration',
                        ]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="six columns">
                        {!! Form::dateField('issued_date', 'Issued Date') !!}
                    </div>
                    <div class="six columns">
                        {!! Form::dateField('validity_date', 'Validity Date') !!}
                    </div>
                </div>
                <div class="row">
                    <div class="twelve columns dropzone">
                        <input  type="file" name="file" multiple/>
                    </div>
                </div>

                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="_method" type="hidden" value="POST">
            </div>

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>