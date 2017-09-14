<nav class="nav nav-modified has-shadow">
    <div class="container">
        <div class="nav-left">
            <a href="{{ route('home.index') }}" class="nav-item m-l-10">Home</a>
        </div>

        <div class="nav-right is-hidden-mobile" style="overflow: visible;">
            @if (Auth::guest())
                @if(\Config::get('custom.auth.login') === true)
                <a href="{{ route('login') }}" class="nav-item is-tab">Login</a>
                @endif
                @if(\Config::get('custom.auth.register') === true)
                <a href="{{ route('register') }}" class="nav-item is-tab">Sign Up</a>
                @endif
            @else
                <b-dropdown name="navigation.top" v-model="navigation" position="is-bottom-left" class="dropdown nav-item is-tab">
                    <a class="nav-item" slot="trigger">
                        <span class="m-r-5">{{ Auth::user()->name }}</span>
                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                    </a>

                    <!--<b-dropdown-option class="has-text-centered"><a href="#">Profile</a></b-dropdown-option>
                    <b-dropdown-option class="has-text-centered"><a href=" route('settings.index') ">Settings</a></b-dropdown-option>-->
                    @if(\Laratrust\LaratrustFacade::can('read-managment'))
                    <b-dropdown-option class="has-text-centered" name="navigation.dashboard"><a href="{{ route('manage.dashboard') }}">Management</a></b-dropdown-option>
                    <div class="seperator"></div>
                    @endif
                    <b-dropdown-option class="has-text-centered" name="navigation.logout">
                        <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>

                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </b-dropdown-option>
                </b-dropdown>
            @endif
        </div>

    </div>
</nav>
