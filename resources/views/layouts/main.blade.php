<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>AFPPS END-USER KIOSK</title>
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
            .preloader p{position:absolute;top:50%;left:50%;margin-top:-32px;margin-left:-32px}
        </style>

    </head>

    <body class="o-body o-body--outline">>

    <div class="o-container"  id="app">

      @yield('modal')
      <div class="o-container__wrapper">
          @include('layouts.topbar')
          @include('layouts.sidebar')

          <div class="o-content">
            <div class="o-content__wrapper">

                <div class="row">
                    <div class="preloader">
                        <img src="/img/preloader.gif" alt="">
                        <p>Loading...</p>
                    </div>

                    {{-- @include('layouts.alerts') --}}
                    @yield('contents')
                </div>
            </div>
        </div>
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
