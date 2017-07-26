<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>AFP Procurement Service</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- app icon -->
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <link rel="icon" type="image/png" href="/favicon.png">

        @include('layouts.styles')
        @yield('styles')

    </head>

    <body>

    <div class="container">
        {{-- Modal --}}
        @yield('modal')
        {{-- Modal --}}
        <!-- topbar -->
        @include('layouts.topbar')
        <!-- topbar -->
        <!-- sidebar -->
        @include('layouts.sidebar')
        {{-- sidebar --}}

        <!-- chat support -->
        <div class="chat">
            <div class="chat__head">
                <div class="chat__head__title">Super Chat</div>
                <div class="chat__head__utility">
                    <!-- <button class="chat__head__utility__button minimize-chat">
                        <i class="nc-icon-mini ui-1_simple-delete"></i>
                    </button> -->
                    <button class="chat__head__utility__button close-chat">
                        <i class="nc-icon-mini ui-1_simple-remove"></i>
                    </button>
                </div>
            </div>
            <div class="chat__thread">
                <div class="chat__thread__item">
                    <span class="chat__thread__avatar"><img src="src/img/avatar.jpg" alt=""></span>
                    <span class="chat__thread__info">
                        <span class="chat__thread__name">Support</span>
                        <span class="chat__thread__message">Hello, How are you?</span>
                    </span>
                </div>
                <div class="chat__thread__breaker">
                    <span class="chat__thread__breaker__time">2h ago</span>
                </div>
                <div class="chat__thread__item chat__thread__item--you">
                    <span class="chat__thread__avatar"><img src="src/img/avatar.jpg" alt=""></span>
                    <span class="chat__thread__info">
                        <span class="chat__thread__name">You</span>
                        <span class="chat__thread__message">I'm fine!</span>
                    </span>
                </div>
                <div class="chat__thread__item">
                    <span class="chat__thread__avatar"><img src="src/img/avatar.jpg" alt=""></span>
                    <span class="chat__thread__info">
                        <span class="chat__thread__name">Support</span>
                        <span class="chat__thread__message">Good to hear!</span>
                    </span>
                </div>
                <div class="chat__thread__item chat__thread__item--you">
                    <span class="chat__thread__avatar"><img src="src/img/avatar.jpg" alt=""></span>
                    <span class="chat__thread__info">
                        <span class="chat__thread__name">You</span>
                        <span class="chat__thread__message">Give me mana punk!</span>
                        <span class="chat__thread__message">I can't skill</span>
                    </span>
                </div>
                <div class="chat__thread__item">
                    <span class="chat__thread__avatar"><img src="src/img/avatar.jpg" alt=""></span>
                    <span class="chat__thread__info">
                        <span class="chat__thread__name">Support</span>
                        <span class="chat__thread__message">Okay, thanks! bye!</span>
                    </span>
                </div>
                <div class="chat__thread__breaker">
                    <span class="chat__thread__breaker__time">25m ago</span>
                </div>
                <div class="chat__thread__item chat__thread__item--you">
                    <span class="chat__thread__avatar"><img src="src/img/avatar.jpg" alt=""></span>
                    <span class="chat__thread__info">
                        <span class="chat__thread__name">You</span>
                        <span class="chat__thread__message">...</span>
                    </span>
                </div>
            </div>
            <div class="chat__compose">
                <textarea class="chat__compose__textarea" name="" id="" cols="30" rows="3" placeholder="Write your message here"></textarea>
                <div class="chat__compose__utility">
                    <span class="chat__compose__word-count">350</span>
                    <span class="chat__compose__options">
                        <a href="" class="chat__compose__options__item">Option A</a>
                        <a href="" class="chat__compose__options__item">Option B</a>
                    </span>
                </div>
            </div>
        </div>


        <!-- content -->
        <div class="content">
            <div class="content__wrapper">
                <div class="row">

                    @include('layouts.alerts')
                    @yield('contents')
                </div>
            </div>
        </div>
        <!-- content -->
    </div>

    @include('layouts.scripts')
    @yield('scripts')
    <script type="text/javascript">

         $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });
     </script>
    </body>
</html>
