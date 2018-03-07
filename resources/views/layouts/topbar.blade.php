<?php
$currentRoute = Route::currentRouteName();

$cRoute = explode('.', $currentRoute);
?>

<div class="topbar">
    <div class="topbar__wrapper">
        <div class="topbar__left-panel">
            <h1 class="topbar__page-title">@yield('title')</h1>
            <div class="topbar__breadcrumbs">
               {{--  @foreach($cRoute as $route)
                    @if(!in_array($route, ['index', 'edit', 'create', 'show']))
                        @if(route::has($route.".index"))
                            <a href="{{route($route.'.index')}}" class="topbar__breadcrumbs__item">{{$route}}</a>
                        @else
                            <a href="#" class="topbar__breadcrumbs__item">{{$route}}</a>
                        @endif
                    @endif
                @endforeach --}}

                @yield('breadcrumbs')

            </div>
        </div>
        <div class="topbar__right-panel">
            <div class="topbar__utility">
                <a href="{{route('notifications.index')}}" class=""  position="bottom" tooltip="Delays">
                    <button type="button" class="topbar__utility__button">
                        @if($delayCounts >= 1)
                            <span class="topbar__utility__button__badge">{{$delayCounts}}</span>
                        @endif
                            <i class="nc-icon-mini ui-3_alert"></i>
                    </button>
                </a>
                <a href="{{route('notify.index')}}" class="" position="bottom" tooltip="Notifications">
                    <button type="button" class="topbar__utility__button">
                        @if($notifCount >= 1)
                            <span class="topbar__utility__button__badge">{{$notifCount}}</span>
                        @endif
                            <i class="nc-icon-mini ui-1_bell-53"></i>
                    </button>
                </a>
                <a href="{{route('change-logs.index')}}"  position="bottom" tooltip="Change Logs">
                    <button type="button" class="topbar__utility__button">
                        @if($logCounts >= 1)
                            <span class="topbar__utility__button__badge">{{$logCounts}}</span>
                        @endif
                            <i class="nc-icon-mini ui-1_notification-69"></i>
                    </button>
                </a>
                @if(!Sentinel::getUser()->hasRole('Admin'))
                    @if($myMessages < 1)
                        <button type="button" class="topbar__utility__button topbar__utility__button--chat open-chat">
                            @if($messageCount >= 1)
                                <span class="topbar__utility__button__badge">{{$messageCount}}</span>
                            @endif
                            <i class="nc-icon-mini ui-2_chat-round"></i>
                        </button>
                    @else

                        <button {{-- v-link="'{{route('messages.admin')}}'" --}} type="button" class="topbar__utility__button  open-inbox">
                            @if($messageCount >= 1)
                                <span class="topbar__utility__button__badge">{{$messageCount}}</span>
                            @endif
                            <i class="nc-icon-mini ui-2_chat-round"></i>
                        </button>
                    @endif
                @else
                    <button {{-- v-link="'{{route('messages.admin')}}'" --}} type="button" class="topbar__utility__button open-inbox">
                        @if($messageCount >= 1)
                            <span class="topbar__utility__button__badge">{{$messageCount}}</span>
                        @endif
                        <i class="nc-icon-mini ui-2_chat-round"></i>
                    </button>
                @endif
            </div>
            <div class="topbar__user">
                <div class="topbar__user__info">
                    <span class="topbar__user__info__name">{{$currentUser->first_name}} {{$currentUser->surname}}</span>
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