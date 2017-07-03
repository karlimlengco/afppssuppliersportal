<div class="modal" id="accept-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="accept-form" action="{{route('procurements.inspection-and-acceptance.accepted',$data->id)}}" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Confirm Acceptance</h1>
            </div>

            <div class="modal__dialogue__body">
                {!! Form::textField('accepted_date', 'Accepted Date') !!}
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="_method" type="hidden" value="POST">
            </div>

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>