<div class="row">
    <div class="col-md-12">

        @if(Session::has('error'))
        <div class="message-box message-box--large message-box--error" role="alert">
            <span class="message-box__icon"><i class="nc-icon-outline ui-2_ban"></i></span>
            <span class="message-box__message">Oh No!! {{Session::get('error')}}</span>
        </div>
        @endif

        @if(Session::has('errors'))
        <div class="message-box message-box--large message-box--error" role="alert">
            <span class="message-box__icon"><i class="nc-icon-outline ui-2_ban"></i></span>

            <span class="message-box__message">Oh No!! Something went wrong. {{Session::get('error-msg')}}</span>
        </div>
        @endif

        @if(Session::has('success'))
        <div class="message-box message-box--large message-box--success" role="alert">
            <span class="message-box__icon"><i class="nc-icon-outline ui-1_check-circle-08"></i></span>
            <span class="message-box__message">{{Session::get('success')}}</span>
        </div>

        @endif

    </div>
</div>