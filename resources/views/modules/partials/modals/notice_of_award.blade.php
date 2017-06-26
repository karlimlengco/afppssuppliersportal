<div class="modal" id="award-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
        <form method="POST" id="award-form" action="#" accept-charset="UTF-8">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Award Confirmation</h1>
            </div>

            <div class="modal__dialogue__body">
                <p>You will award this to <strong><span id='proponent'></span></strong></p>
                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                <input name="_method" type="hidden" value="POST">
            </div>

            <div class="modal__dialogue__foot">
                <button class="button">Proceed</button>
            </div>

        </form>
    </div>
</div>