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
        {{-- @include('layouts.topbar') --}}
        <!-- topbar -->
        <!-- sidebar -->
        {{-- @include('layouts.sidebar') --}}
        {{-- sidebar --}}

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
