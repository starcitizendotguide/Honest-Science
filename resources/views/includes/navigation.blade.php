<nav class="navbar nav-modified">
    <div class="navbar-menu">
        <div class="navbar-start">
            <a href="{{ route('home.index') }}" class="navbar-item m-l-10">Home</a>
            <a href="{{ route('about.index') }}" class="navbar-item m-l-10">About</a>
        </div>

        <div class="navbar-end is-hidden-mobile" style="overflow: visible;">
            @if (Auth::guest())
                @if(\Config::get('custom.auth.login') === true)
                <a href="{{ route('login') }}" class="navbar-item is-tab">Login</a>
                @endif
            @else
                <b-dropdown name="navigation.top" v-model="navigation" position="is-bottom-left" class="dropdown navbar-item is-tab">
                    <a class="navbar-item" slot="trigger">
                        <span class="m-r-5">{{ Auth::user()->name }}</span>
                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                    </a>

                    <!--<b-dropdown-option class="has-text-centered"><a href="#">Profile</a></b-dropdown-option>
                    <b-dropdown-option class="has-text-centered"><a href=" route('settings.index') ">Settings</a></b-dropdown-option>-->
                    @if(\Laratrust\LaratrustFacade::can('read-managment'))
                    <b-dropdown-item class="has-text-centered" name="navigation.dashboard"><a href="{{ route('manage.dashboard') }}">Management</a></b-dropdown-item>
                    <div class="seperator"></div>
                    @endif
                    <b-dropdown-item class="has-text-centered" name="navigation.logout">
                        <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>

                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </b-dropdown-item>
                </b-dropdown>
            @endif
        </div>

    </div>
</nav>
