<?php
/**
 * Sidebar Generator for Administrator
 */
$currentRoute = Route::currentRouteName();
$sidebar      = new \Revlv\Sidebar\SidebarGenerator($currentRoute);
?>

<div class="sidebar">
    <!-- logo -->
    <div class="sidebar__logo">
        {{-- <h1 class="sidebar__logo__type">AFP</h1> --}}
        <span class="sidebar__logo__icon"><img src="/img/logo.png" alt=""></span>
    </div>

    <!-- search -->
    <div class="sidebar__search">
        <input type="text" class="sidebar__search__input" placeholder="Looking for something?">
        <button class="sidebar__search__button"><i class="nc-icon-mini ui-1_zoom"></i></button>
    </div>

    <!-- menu -->
    <ul class="sidebar__menu">
        <li class="sidebar__menu__item">
            <a href="" class="sidebar__menu__item__link">
                <i class="nc-icon-mini business_chart-bar-32"></i>Overview
            </a>
        </li>
        @foreach($sidebar->getSidebar() as $group => $route)
        <li class="sidebar__menu__item has-child">

            @if(count($route->navigation) > 1)
                <a href="#" class="sidebar__menu__item__link">
                    <i class="{{$route->icon}}"></i>{{$route->name}}
                </a>

                <ul class="sidebar__child-menu">
                    @foreach($route->navigation as $nav)
                        <li class="sidebar__menu__item">
                            <a href="{{route($nav->route)}}" class="sidebar__menu__item__link">{{$nav->name}}</a>
                        </li>
                    @endforeach
                </ul>
            @else
                @foreach($route->navigation as $nav)
                <a href="{{route($nav->route)}}" class="sidebar__menu__item__link">
                    <i class="{{$route->icon}}"></i>{{$route->name}}
                </a>
                @endforeach
            @endif
        </li>
        @endforeach
    </ul>


</div>