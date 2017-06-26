<div class="modal" id="close-modal">
    <div class="modal__dialogue modal__dialogue--round-corner">
            <button type="button" class="modal__close-button">
                <i class="nc-icon-outline ui-1_simple-remove"></i>
            </button>

            <div class="moda__dialogue__head">
                <h1 class="modal__title">Closing RFQ</h1>
            </div>

            <div class="modal__dialogue__body">
                <p>After closing this Request for Quotation you will not be able to add proponents or update.</p>
            </div>

            <div class="modal__dialogue__foot">
                <a href="{{route('procurements.blank-rfq.closed',$data->id)}}" class="button">Proceed</a>
            </div>
    </div>
</div>