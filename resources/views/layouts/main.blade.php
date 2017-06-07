<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>DOCU PARSER</title>

        @include('layouts.styles')
        @yield('styles')
    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">

                <div class="col-md-3 left_col">
                    <div class="left_col scroll-view">
                        <div class="navbar nav_title" style="border: 0;">
                            <a href="#" class="site_title">
                                <i class="nc-icon-outline files_replace" ></i>
                                <span>DOCU PARSER</span>
                            </a>
                        </div>

                    <div class="clearfix"></div>

                    <!-- menu profile quick info -->
                    @include('layouts.profile')
                    <!-- /menu profile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    @include('layouts.sidebar')
                    <!-- /sidebar menu -->


                    </div>
                </div>

                <!-- top navigation -->
                @include('layouts.top-nav')
                <!-- /top navigation -->

                <!-- page content -->
                <div class="right_col" role="main">
                    <div class="">
                        @include('layouts.alerts')
                        @yield('contents')
                    </div>
                </div>
                <!-- /page content -->

                <!-- footer content -->
                @include('layouts.footer')
                <!-- /footer content -->
            </div>
        </div>
        @include('layouts.scripts')
        @yield('scripts')
    </body>
</html>
