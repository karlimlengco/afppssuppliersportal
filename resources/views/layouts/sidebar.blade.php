<?php
/**
 * Sidebar Generator for Administrator
 */
$currentRoute = Route::currentRouteName();
$sidebar      = new \Revlv\Sidebar\SidebarGenerator($currentRoute);
?>


<div class="p-sidebar">
  <div class="c-branding">
    <div class="c-branding__logo">
      <button class="c-button c-button--circle js-hide-sidebar-button">
        <i class="nc-icon-mini ui-1_simple-remove"></i>
      </button>
      <img src="/img/logo.png" alt="">
    </div>
  </div>
  <div class="c-navlinks c-navlinks--vertical">
    @foreach($sidebar->getSidebar() as $group => $route)
        @if(explode('.', $currentRoute)[0] == $route->subname)
            <div class="c-navlinks__item c-navlinks__item--active">
              <a href="{{route($route->route)}}" class="c-navlinks__link">
                <span class="c-navlinks__icon"><i class="{{$route->icon}}"></i></span>
                <span class="c-navlinks__label">{{$route->name}}</span>
              </a>
                @if(explode('.', $currentRoute)[0] == $route->subname)

                    @foreach($route->navigation as $nav)
                        <div class="c-navlinks__child">
                          <div class="c-navlinks__item">
                            <a href="{{route($nav->route)}}" class="c-navlinks__link">{{$nav->name}}</a>
                          </div>
                        </div>
                    @endforeach
                @endif
            </div>
        @else
            <div class="c-navlinks__item ">
              <a href="{{route($route->route)}}" class="c-navlinks__link">
                <span class="c-navlinks__icon"><i class="{{$route->icon}}"></i></span>
                <span class="c-navlinks__label">{{$route->name}}</span>
              </a>
            </div>
        @endif
    @endforeach
  </div>
</div>
 