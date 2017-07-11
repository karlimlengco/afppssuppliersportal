<?php
$currentRoute = Route::currentRouteName();

$cRoute = explode('.', $currentRoute);
?>

<div class="topbar">
    <div class="topbar__wrapper">
        <div class="topbar__left-panel">
            <h1 class="topbar__page-title">@yield('title')</h1>
            <div class="topbar__breadcrumbs">
                @foreach($cRoute as $route)
                    @if(!in_array($route, ['index', 'edit', 'create', 'show']))
                        @if(route::has($route.".index"))
                            <a href="{{route($route.'.index')}}" class="topbar__breadcrumbs__item">{{$route}}</a>
                        @else
                            <a href="#" class="topbar__breadcrumbs__item">{{$route}}</a>
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
        <div class="topbar__right-panel">
            <div class="topbar__utility">
                <a href="{{route('notifications.index')}}" class="">
                    <button class="topbar__utility__button">
                        @if($delayCounts >= 1)
                            <i class="red nc-icon-mini ui-1_bell-53"></i>
                        @else
                            <i class="nc-icon-mini ui-1_bell-53"></i>
                        @endif
                    </button>
                </a>
                <a href="{{route('change-logs.index')}}">
                    <button class="topbar__utility__button">
                        @if($logCounts >= 1)
                            <i class="red nc-icon-mini ui-1_notification-69"></i>
                        @else
                            <i class="nc-icon-mini ui-1_notification-69"></i>
                        @endif
                    </button>
                </a>
                <button class="topbar__utility__button topbar__utility__button--chat"><i class="nc-icon-mini ui-2_chat-round"></i></button>
            </div>
            <div class="topbar__user">
                <div class="topbar__user__info">
                    <span class="topbar__user__info__name">{{$currentUser->first_name}}, {{$currentUser->surname}}</span>
                    <span class="topbar__user__info__dept">{{$currentUser->designation}}</span>
                </div>
                <div class="topbar__user__avatar">
                    <!-- DC -->
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
</div>