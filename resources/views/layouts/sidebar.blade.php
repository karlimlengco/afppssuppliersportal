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
            <li>
                <a href="{{route('dashboard.index')}}">
                    <i class="nc-icon-glyph ui-1_home-52 lg"></i>
                    Main Menu&nbsp;&nbsp;
                </a>
            </li>
            @foreach($sidebar->getSidebar() as $group => $route)
                @if($currentRoute == $route->route)
                    @foreach($route->navigation as $nav)

                        <li>
                            <a href="{{route($nav->route)}}">
                                <i class="{{$nav->icon}}"></i>
                                {{$nav->name}}
                            </a>
                        </li>
                    @endforeach
                @else
                    <li>
                        <a href="{{route($route->route)}}">
                            <i class="{{$route->icon}}"></i>
                            {{$route->name}} &nbsp;&nbsp;
                        </a>
                    </li>
                @endif
            @endforeach

        </ul>
    </div>

</div>