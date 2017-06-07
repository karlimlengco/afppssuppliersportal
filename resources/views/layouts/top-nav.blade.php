<div class="top_nav">
    <div class="nav_menu">
        <nav class="" role="navigation">
            <div class="nav toggle">
                <a id="menu_toggle"><i class="nc-icon-outline ui-2_menu-34"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;"
                        class="user-profile dropdown-toggle"
                        data-toggle="dropdown"
                        aria-expanded="false">
                        <img src="{{route('user.avatar.show', $currentUser->id)}}" alt="">{{$currentUser->first_name}}, {{$currentUser->surname}}
                        <span class=" fa fa-angle-down"></span>
                    </a>

                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li>
                            <a href="{{route('profile.show')}}"> Profile</a>
                        </li>
                        <li>
                            <a href="{{route('auth.logout')}}"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>