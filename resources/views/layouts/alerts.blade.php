{{-- <div class="row">
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
</div> --}}

<!-- default: gray or
     add class notifier--error / notifier--warning / notifier--success
     for different notifications -->

@if(Session::has('error'))
<div class="notifier notifier--error is-visible">
    <a href="" class="notifier__close-button"><i class="nc-icon-mini ui-1_simple-remove"></i></a>
    <h1 class="notifier__title">Oh No!</h1>
    <p class="notifier__message">{{Session::get('error')}}</p>
    <div class="notifier__utility">
        {{-- <a href="" class="notifier__utility__item">Nope</a> --}}
        <a href="" class="notifier__utility__item">Okay</a>
    </div>
</div>
@endif

@if(Session::has('errors'))
<div class="notifier notifier--error is-visible">
    <a href="" class="notifier__close-button"><i class="nc-icon-mini ui-1_simple-remove"></i></a>
    <h1 class="notifier__title">Oh No!</h1>
    <p class="notifier__message">{{Session::get('error-msg')}}</p>
    <div class="notifier__utility">
        <a href="" class="notifier__utility__item">Okay</a>
    </div>
</div>
@endif

@if(Session::has('success'))

<div class="notifier notifier--success is-visible">
    <a href="" class="notifier__close-button"><i class="nc-icon-mini ui-1_simple-remove"></i></a>
    <h1 class="notifier__title">Success</h1>
    <p class="notifier__message">{{Session::get('success')}}</p>
    <div class="notifier__utility">
        <a href="" class="notifier__utility__item">Okay</a>
    </div>
</div>
@endif