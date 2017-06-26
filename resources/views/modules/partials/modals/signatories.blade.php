<div class="modal" id="signatory-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="delete-form"  action="{{route('procurements.canvassing.signatories', $data->id)}}" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Signatory</h1>
            </div>

            <div class="modal__dialogue__body">
                <div class="row">
                    <div class="twelve columns">
                        {!! Form::tagField('signatory_id', '', $signatory_lists, (count( $data->signatories ) != 0 ) ? $data->signatories->pluck('signatory_id')->toArray()   : "") !!}
                    </div>
                </div>
            </div>

            <input name="_token" type="hidden" value="{{ csrf_token() }}">
            <input name="_method" type="hidden" value="POST">

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>