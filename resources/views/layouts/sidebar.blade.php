<?php
/**
 * Sidebar Generator for Administrator
 */
$currentRoute = Route::currentRouteName();
$sidebar      = new \Revlv\Sidebar\SidebarGenerator($currentRoute);
?>

<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
        <h3>&nbsp;</h3>
        <ul class="nav side-menu">
            @foreach($sidebar->getSidebar() as $group => $route)
                <li>
                    @if(count($route->navigation) > 1)
                        <a>
                        <i class="{{$route->icon}}"></i>
                            {{$route->name}} &nbsp;&nbsp;
                        <span class="nc-icon-glyph arrows-3_small-down lg"></span>
                        </a>
                        <ul class="nav child_menu">
                            @foreach($route->navigation as $nav)
                                <li><a href="{{route($nav->route)}}">{{$nav->name}}</a></li>
                            @endforeach
                        </ul>
                    @else
                        @foreach($route->navigation as $nav)
                        <a href="{{route($nav->route)}}">
                            <i class="{{$route->icon}}"></i>
                            {{$route->name}}
                        </a>
                        @endforeach
                    @endif
                </li>
            @endforeach

        </ul>
    </div>

</div>