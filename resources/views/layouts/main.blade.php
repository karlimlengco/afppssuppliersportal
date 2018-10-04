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
        <style>
            .preloader{position:fixed;left:0;top:0;z-index:102;width:100%;height:100vh;overflow:hidden;background:#fff; opacity: .7}
            .preloader img{position:absolute;top:50%;left:50%;margin-top:-32px;margin-left:-32px}
        </style>

    </head>

    <body>

    <div class="container"  id="app">

            
        @yield('modal')
        @include('layouts.sidebar')
        <div >
            @include('layouts.topbar')
            {{-- sidebar --}}

            <!-- content -->
            <div class="content">


        <!-- preloader -->
    
                <div class="content__wrapper">
                    <div class="row">
                        <div class="preloader">
                            <img src="/img/preloader.gif" alt="">
                        </div>

                        @include('layouts.alerts')
                        @yield('contents')
                    </div>
                </div>
            </div>
            <!-- content -->
            {{-- Chat --}}
            <div class="chat">
                <div class="chat__head">
                    <div class="chat__head__title" id="chatHead">Admin</div>
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
                    <chat-log :messages="messages" ></chat-log>
                </div>
                <chat-composer v-on:messagesent="addMessage"></chat-composer>

            </div>
            {{-- Chat --}}

            <!-- chat inbox -->
            {{--  is-visible --}}
            <div class="inbox">
                <div class="inbox__data">
                    <button class="inbox__close">
                        <i class="nc-icon-outline ui-1_simple-remove"></i>
                    </button>
                    <admin-messages ></admin-messages>
                </div>
            </div>
            <!-- chat inbox -->

        </div>
    </div>

    @include('layouts.footer')
    @include('layouts.scripts')
    
    @yield('scripts')
    <script type="text/javascript">
    /* preloader */ 
         $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             }
         });
     </script>
    </body>
</html>
