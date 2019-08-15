<?php
$currentRoute = Route::currentRouteName();

$cRoute = explode('.', $currentRoute);
?>

<div class="p-topbar">
  <div class="p-topbar__wrapper">
    <div class="p-topbar__panel">
      <div class="c-button-group u-pos-left">
        <button class="c-button c-button--circle js-toggle-sidebar-button">
          <i class="nc-icon-mini arrows-1_tail-left"></i>
        </button>
        <button class="c-button c-button--circle js-show-sidebar-button">
          <i class="nc-icon-mini ui-2_menu-34"></i>
        </button>
      </div> 
      <div class="p-topbar__user">
        <div class="c-dropdown c-dropdown--right">
          <div class="p-topbar__user js-show-user-settings">
            <div class="p-topbar__user__info">
              <span class="c-data">{{$currentUser->first_name}}</span>
            </div>
            <div class="c-avatar c-avatar--circle c-avatar--small">
              <div class="c-actions">
                <button class="c-button c-button--icon">
                  <i class="nc-icon-mini ui-1_settings-gear-65"></i>
                </button>
              </div>
              <img src="{{route('user.avatar.show', $currentUser->id)}}" alt="">
            </div>
          </div>
          <div class="c-dropdown__menu">
            <div class="c-navlinks">
              <div class="c-navlinks__item">
                <a href="{{route('profile.show')}}" class="c-navlinks__link">Profile Settings</a>
              </div>
              <div class="c-navlinks__item">
                <a  href="{{route('auth.logout')}}"  class="c-navlinks__link">Sign-out</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="p-topbar__panel">
        @yield('breadcrumbs')
    </div>
  </div>
</div>

{{-- <div class="topbar">
    <div class="topbar__wrapper">
        <div class="topbar__left-panel">
            <h1 class="topbar__page-title">@yield('title')</h1>
            <div class="topbar__breadcrumbs"> 
                @yield('breadcrumbs')

            </div>
        </div>
        <div class="topbar__right-panel">
            <div class="topbar__utility">
            </div>
            <div class="topbar__user">
                <div class="topbar__user__info">
                    <span class="topbar__user__info__name">{{$currentUser->first_name}}</span> 
                </div>
                <div class="topbar__user__avatar"> 
                    <img src="{{route('user.avatar.show', $currentUser->id)}}" alt="">
                </div>
                <ul class="topbar__user__options">
                    <li class="topbar__user__options__item">
                        <a href="{{route('profile.show')}}" class="topbar__user__options__item__link">Edit Profile</a>
                    </li>
                    <li class="topbar__user__options__item">
                        <a href="" class="topbar__user__options__item__link">User Settings</a>
                    </li>
                    <li class="topbar__user__options__item">
                        <a href="{{route('auth.logout')}}" class="topbar__user__options__item__link">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div> --}}